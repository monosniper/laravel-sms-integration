<?php

namespace KiranoDev\LaravelSms\Contracts;

interface TemplateInterface
{
    public function message(): string;
}