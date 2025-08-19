<?php


namespace Monosniper\LaravelSms\Services\Sms;

use Monosniper\LaravelSms\Abstract\Template;
use Monosniper\LaravelSms\Contracts\SmsService;

class SmsTemp implements SmsService
{
    public function send(?string $phone, Template $template): void
    {
        info($phone);
        info($template->message());
    }
}
