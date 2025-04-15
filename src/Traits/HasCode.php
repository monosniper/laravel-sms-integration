<?php

namespace KiranoDev\LaravelSms\Traits;

use KiranoDev\LaravelSms\Services\Generators\Code;
use Random\RandomException;

trait HasCode
{
    /**
     * @throws RandomException
     */
    protected function generateCode(): void
    {
        $this->updateQuietly([
            'code' => Code::generate()
        ]);
    }
}