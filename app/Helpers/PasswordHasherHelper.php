<?php

namespace App\Helpers;

use RuntimeException;

class PasswordHasherHelper
{

    /**
     * Hash the given value.
     *
     * @param  string $value
     * @param  array  $options
     * @return string
     * @throws RuntimeException
     */
    public static function make($value, array $options = [])
    {
        return md5(md5($value));
    }
    /**
     * Check the given plain value against a hash.
     *
     * @param  string $value
     * @param  string $hashedValue
     * @param  array  $options
     * @return bool
     */
    public static function check($value, $hashedValue, array $options = [])
    {
        if (strlen($hashedValue) === 0) {
            return false;
        }
        return $hashedValue == md5(md5($value));
    }
}
