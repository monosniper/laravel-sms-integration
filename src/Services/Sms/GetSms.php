<?php

namespace KiranoDev\LaravelSms\Services\Sms;

use KiranoDev\LaravelSms\Abstract\Template;
use KiranoDev\LaravelSms\Contracts\SmsService;

class GetSms implements SmsService
{
    const HOST = 'http://185.8.212.184/smsgateway/';

    private string $prefix;
    private string $login;
    private string $password;

    public function __construct()
    {
        $this->prefix = config('sms.getsms.prefix');
        $this->login = config('sms.getsms.login');
        $this->password = config('sms.getsms.password');
    }

    public function send(?string $phone, Template $template): void
    {
        if($phone) {
            $phone = str_replace(['+', ' '], '', $phone);
            $curl = curl_init();
            $sms = [
                [
                    'phone' => $phone,
                    'text'  => $this->prefix . $template->message(),
                ],
            ];
            $data = 'login='.urlencode($this->login);
            $data .= '&password='.urlencode($this->password);
            $data .= '&data='.urlencode(json_encode($sms));
            curl_setopt($curl, CURLOPT_URL, self::HOST);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($curl, CURLOPT_TIMEOUT, 5);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_USERAGENT, 'Opera 10.00');
            $response = curl_exec($curl);
            info(json_encode($response));
        }
    }
}
