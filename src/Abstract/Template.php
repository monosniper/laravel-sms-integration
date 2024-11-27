<?php

namespace KiranoDev\LaravelSms\Abstract;

use KiranoDev\LaravelSms\Contracts\TemplateInterface;

class Template implements TemplateInterface
{
    private string $name = '';

    public function message(): string
    {
        return __("sms.$this->name");
    }
}