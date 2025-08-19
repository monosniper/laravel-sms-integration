<?php

namespace Monosniper\LaravelSms\Services\Generators;

use InvalidArgumentException;
use Throwable;

final class SmsCode
{
    public const DEFAULT_LENGTH = 4;

    public static function generate(int $length = self::DEFAULT_LENGTH): string
    {
        if ($length < 1) {
            throw new InvalidArgumentException('SMS code length must be at least 1.');
        }

        $min = 10 ** ($length - 1);
        $max = (10 ** $length) - 1;

        try {
            $number = random_int($min, $max);
        } catch (Throwable) {
            $number = mt_rand($min, $max);
        }

        return str_pad((string) $number, $length, '0', STR_PAD_LEFT);
    }
}
