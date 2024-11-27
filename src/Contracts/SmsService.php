<?php

namespace KiranoDev\LaravelSms\Contracts;

use KiranoDev\LaravelSms\Abstract\Template;

interface SmsService
{
    public function send(string $phone, Template $template): void;
}
