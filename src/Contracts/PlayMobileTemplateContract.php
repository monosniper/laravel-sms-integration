<?php

namespace Monosniper\LaravelSms\Contracts;

interface PlayMobileTemplateContract
{
    public function getTemplateId(): int;
    public function getVariables(): array;
}