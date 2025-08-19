<?php

namespace Monosniper\LaravelSms\Contracts;

interface PlayMobileTemplateInterface
{
    public function getTemplateId(): int;
    public function getVariables(): array;
}