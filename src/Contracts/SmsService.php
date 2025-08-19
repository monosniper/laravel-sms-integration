<?php

namespace Monosniper\LaravelSms\Contracts;

use Monosniper\LaravelSms\Abstract\SmsTemplate;

interface SmsService
{
    public function send(string $phone, SmsTemplate $template): void;
}
