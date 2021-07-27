<?php

namespace App\Services\Helpers;

/**
 * Class Integer
 * @package App\Services\Helpers
 */
class Integer
{
    /**
     * Check input number is positive integer
     * @param int|string $number  Input number
     * @return bool
     */
    public static function isPositive($number): bool
    {
        if (filter_var($number, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) !== false) {
            return true;
        }

        return false;
    }

    /**
     * Check input number is positive integer and zero
     * @param int|string $number  Input number
     * @return bool
     */
    public static function isNotNegative($number): bool
    {
        if (filter_var($number, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) !== false) {
            return true;
        }

        return false;
    }
}
