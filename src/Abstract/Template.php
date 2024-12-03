<?php

namespace KiranoDev\LaravelSms\Abstract;

use KiranoDev\LaravelSms\Contracts\TemplateInterface;

class Template implements TemplateInterface
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