<?php

namespace Monosniper\LaravelSms\Abstract;

use Monosniper\LaravelSms\Contracts\SmsTemplateContract;

class SmsTemplate implements SmsTemplateContract
{
    protected string $name = '';

    public function message(): string
    {
        return __("sms.$this->name", $this->getParams());
    }

    protected function getParams(): array {
        return [];
    }
}