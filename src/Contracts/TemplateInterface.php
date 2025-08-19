<?php

namespace Monosniper\LaravelSms\Contracts;

interface TemplateInterface
{
    public function message(): string;
}