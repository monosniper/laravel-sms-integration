<?php

namespace Monosniper\LaravelSms\Services\Generators;

use Random\RandomException;

class Code
{
    static public function generate(): ?int
    {
        try {
            return random_int(1111, 9999);
        } catch (RandomException $e) {
            return null;
        }
    }
}