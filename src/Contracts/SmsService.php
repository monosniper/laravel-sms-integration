<?php

namespace Monosniper\LaravelSms\Contracts;

use Monosniper\LaravelSms\Abstract\Template;

interface SmsService
{
    public function send(string $phone, Template $template): void;
}
