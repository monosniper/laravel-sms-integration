<?php


namespace Monosniper\LaravelSms\Services\Sms;

use Illuminate\Support\Facades\Http;
use Monosniper\LaravelSms\Abstract\Template;
use Monosniper\LaravelSms\Contracts\SmsService;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberUtil;

class Eskiz implements SmsService
{
    private ?PhoneNumber $numberProto;
    private string $country_code;
    private string $phone;
    private string $email;
    private string $password;
    private string $token;
    public string $prefix = '';

    const API_URL = 'https://notify.eskiz.uz/api/';
    const CACHE_TOKEN = 'eskiz_token';

    const ROUTE_LOGIN = 'auth/login';
    const ROUTE_REFRESH = 'auth/refresh';
    const ROUTE_SEND = 'message/sms/send';
    const ROUTE_SEND_GLOBAL = 'message/sms/send-global';

    public function __construct()
    {
        $this->email = config('sms.eskiz.email');
        $this->password = config('sms.eskiz.password');
        $this->token = $this->getToken();
    }

    public function getToken(): string
    {
        if (cache()->has(self::CACHE_TOKEN)) return cache()->get(self::CACHE_TOKEN);

        $token = $this->makeRequest(self::ROUTE_LOGIN, [
            'email' => $this->email,
            'password' => $this->password,
        ], false)['data']['token'];

        cache()->set(self::CACHE_TOKEN, $token);

        return $token;
    }

    public function refreshToken(string $route, array $params = [], bool $withToken = true): void
    {
        cache()->forget(self::CACHE_TOKEN);
        $this->token = $this->makeRequest(self::ROUTE_REFRESH, method: 'patch')['data']['token'];
        cache()->set(self::CACHE_TOKEN, $this->token);
        $this->makeRequest($route, $params, $withToken);
        info('Eskiz refreshing token...');
    }

    public function makeRequest(string $route, array $params = [], bool $withToken = true, string $method = 'post'): ?array
    {
        try {
            $response = Http::withToken($withToken ? $this->token : '')
                ->acceptJson()
                ->$method(self::API_URL . $route, $params);

            if ($response->status() === 403) $this->refreshToken($route, $params, $withToken);

            return $response->json();
        } catch (\Exception $e) {
            info('ESKIZ error: ' . $e->getMessage());
            info('ESKIZ params: ' . json_encode($params));
        }

        return null;
    }

    public function resolveCountryCode(): string
    {
        return app(PhoneNumberUtil::class)->getRegionCodeForNumber(
            $this->numberProto
        );
    }

    public function sendGlobal(Template $template): void
    {
        $this->makeRequest(self::ROUTE_SEND_GLOBAL, [
            'mobile_phone' => $this->phone,
            'message' => $template->message(),
            'country_code' => $this->country_code,
            'unicode' => 1,
        ]);
    }

    public function sendLocal(Template $template): void
    {
        $response = $this->makeRequest(self::ROUTE_SEND, [
            'mobile_phone' => $this->phone,
            'message' => $template->message(),
        ]);
        info('ESKIZ has Expired in message or not');
        info(isset($response['message']));
        info($response['message'] === 'Expired');
        info(isset($response['message']) && $response['message'] === 'Expired');
        if(isset($response['message']) && $response['message'] === 'Expired') {
            $this->refreshToken(self::ROUTE_SEND, [
                'mobile_phone' => $this->phone,
                'message' => $template->message(),
            ]);
        }

        info('ESKIZ message: ' . $template->message());
        info('ESKIZ response: ' . json_encode($response, JSON_UNESCAPED_UNICODE));
    }

    public function getCleanPhone($phone): string
    {
        return str_replace(['+', ' '], '', $phone);
    }

    public function send(string $phone, Template $template): void
    {
        try {
            $this->numberProto = app(PhoneNumberUtil::class)->parse($phone, 'UZ');

            if ($this->numberProto && app(PhoneNumberUtil::class)->isValidNumber($this->numberProto)) {
                $this->country_code = $this->resolveCountryCode();
                $this->phone = $this->getCleanPhone($phone);

                $this->country_code === 'UZ'
                    ? $this->sendLocal($template)
                    : $this->sendGlobal($template);
            } else {
                info('ESKIZ Invalid phone number: ' . $phone);
            }
        } catch (NumberParseException $e) {
            info('ESKIZ Parse phone error: ' . $e->getMessage());
        }
    }
}
