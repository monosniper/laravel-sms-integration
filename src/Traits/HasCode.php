<?php

namespace KiranoDev\LaravelSms\Traits;

use KiranoDev\LaravelSms\Services\Generators\Code;
use Random\RandomException;

trait HasCode
{
    /**
     * @throws RandomException
     */
    public function generateCode(): void
    {
        $this->updateQuietly([ 'code' => Code::generate() ]);
    }

    public function clearCode(): void
    {
        $this->updateQuietly([ 'code' => null ]);
    }
}