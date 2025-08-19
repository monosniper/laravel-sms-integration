<?php

namespace Monosniper\LaravelSms\Services\Generators;

use Random\RandomException;

class Code
{
    /**
     * @throws RandomException
     */
    static public function generate(): int
    {
        return random_int(1111, 9999);
    }
}