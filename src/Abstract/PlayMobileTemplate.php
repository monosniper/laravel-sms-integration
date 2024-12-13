<?php

namespace KiranoDev\LaravelSms\Abstract;

use KiranoDev\LaravelSms\Contracts\PlayMobileTemplateInterface;

abstract class PlayMobileTemplate extends Template implements PlayMobileTemplateInterface
{
    public string $template_id;
}