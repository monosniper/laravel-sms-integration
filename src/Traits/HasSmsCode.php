<?php

namespace Monosniper\LaravelSms\Traits;

use Monosniper\LaravelSms\Services\Generators\Code;

trait HasSmsCode
{
    protected string $column = 'code';

    public function generateCode(): void
    {
        $this->updateQuietly([$this->column => Code::generate()]);
    }

    public function clearCode(): void
    {
        $this->updateQuietly([$this->column => null]);
    }
}