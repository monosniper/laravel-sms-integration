<?php


namespace KiranoDev\LaravelSms\Services\Sms;

use Illuminate\Support\Facades\Http;
use KiranoDev\LaravelSms\Abstract\Template;
use KiranoDev\LaravelSms\Contracts\SmsService;
use libphonenumber\NumberParseException;
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

            return $response->json();
        } catch (\Exception $e) {
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
        $phone1 = $this->getCleanPhone($phone);

        try {
            $numberProto = app(PhoneNumberUtil::class)->parse($phone, 'UZ');

            if ($numberProto && app(PhoneNumberUtil::class)->isValidNumber($numberProto)) {
                $this->makeRequest([
                    'messages' => [
                        [
                            'template-id' => $template->template_id,
                            'message-id' => uniqid(),
                            'recipient' => $phone1,
                            'variables' => $template->getVariables(),
                            'sms' => [
                                'originator' => '3700',
                                'content' => [
                                    'text' => $template->message(),
                                ],
                            ],
                        ]
                    ]
                ]);
            } else {
                info('PlayMobile Invalid phone number: ' . $phone);
            }
        } catch (NumberParseException $e) {
            info('PlayMobile Parse phone error: ' . $e->getMessage());
        }
    }
}
