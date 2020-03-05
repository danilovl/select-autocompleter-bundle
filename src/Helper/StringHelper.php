<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Helper;

class StringHelper
{
    public static function changeToUnderscore(string $string): string
    {
        return strtolower(preg_replace('~([A-Z])~', '_$1', $string));
    }
}
