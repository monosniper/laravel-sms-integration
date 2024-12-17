<?php

namespace KiranoDev\LaravelSms\Contracts;

interface PlayMobileTemplateInterface
{
    public function getTemplateId(): int;
    public function getVariables(): array;
}