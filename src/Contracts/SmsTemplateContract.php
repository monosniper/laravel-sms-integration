<?php

namespace Monosniper\LaravelSms\Contracts;

interface SmsTemplateContract
{
    public function message(): string;
}