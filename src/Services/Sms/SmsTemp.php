<?php


namespace Monosniper\LaravelSms\Services\Sms;

use Monosniper\LaravelSms\Abstract\SmsTemplate;
use Monosniper\LaravelSms\Contracts\SmsService;

class SmsTemp implements SmsService
{
    public function send(?string $phone, SmsTemplate $template): bool
    {
        info($phone);
        info($template->message());

        return true;
    }
}
