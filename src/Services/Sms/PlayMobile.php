<?php


namespace KiranoDev\LaravelSms\Services\Sms;

use Exception;
use Illuminate\Support\Facades\Http;
use KiranoDev\LaravelSms\Abstract\Template;
use KiranoDev\LaravelSms\Contracts\SmsService;
use libphonenumber\PhoneNumberUtil;

class PlayMobile implements SmsService
{
    private string $login;
    private string $password;

    const API_URL = 'https://send.smsxabar.uz/broker-api/send/';

    public function __construct()
    {
        $this->login = config('sms.playmobile.login');
        $this->password = config('sms.playmobile.password');
    }

    public function makeRequest(array $params = []): ?array
    {
        try {
            $response = Http::withBasicAuth($this->login, $this->password)
                ->withHeaders([
                    'Content-type' => 'application/json',
                    'charset' => 'UTF-8',
                ])
                ->post(self::API_URL, $params);

            $rs = $response->json();

            info(json_encode($rs));
            info(json_encode($params));

            return $rs;
        } catch (Exception $e) {
            info('PlayMobile error: ' . $e->getMessage());
            info('PlayMobile params: ' . json_encode($params));
        }

        return null;
    }

    public function getCleanPhone($phone): string
    {
        return str_replace(['+', ' '], '', $phone);
    }

    public function send(string $phone, Template $template): void
    {
        $clean_phone = $this->getCleanPhone($phone);

        try {
            $numberProto = app(PhoneNumberUtil::class)->parse($clean_phone, 'UZ');

            if ($numberProto && app(PhoneNumberUtil::class)->isValidNumber($numberProto)) {
                $this->makeRequest([
                    'messages' => [
                        [
                            'template-id' => $template->getTemplateId(),
                            'message-id' => uniqid(),
                            'recipient' => $clean_phone,
                            'variables' => $template->getVariables(),
                        ]
                    ]
                ]);
            } else {
                info('PlayMobile Invalid phone number: ' . $phone);
            }
        } catch (Exception $e) {
            info('PlayMobile Parse phone error: ' . $e->getMessage());
        }
    }
}
