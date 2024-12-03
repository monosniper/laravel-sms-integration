<?php


namespace KiranoDev\LaravelSms\Services\Sms;

use KiranoDev\LaravelSms\Abstract\Template;
use KiranoDev\LaravelSms\Contracts\SmsService;

class SmsTemp implements SmsService
{
    public function send(?string $phone, Template $template): void
    {
        info($phone);
        info($template->message());
    }
}
