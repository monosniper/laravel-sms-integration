<?php


namespace Monosniper\LaravelSms\Services\Sms;

use Exception;
use Illuminate\Support\Facades\Http;
use Monosniper\LaravelSms\Abstract\SmsTemplate;
use Monosniper\LaravelSms\Contracts\SmsService;
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

    public function makeRequest(array $params = []): bool
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

            return $response->successful();
        } catch (Exception $e) {
            info('PlayMobile error: ' . $e->getMessage());
            info('PlayMobile params: ' . json_encode($params));
        }

        return false;
    }

    public function getCleanPhone($phone): string
    {
        return str_replace(['+', ' '], '', $phone);
    }

    public function send(string $phone, SmsTemplate $template): bool
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

                return true;
            } else {
                info('PlayMobile Invalid phone number: ' . $phone);
            }
        } catch (Exception $e) {
            info('PlayMobile Parse phone error: ' . $e->getMessage());
        }

        return false;
    }
}
