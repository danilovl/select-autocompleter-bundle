<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Helper;

class StringHelper
{
    public static function changeToUnderscore(string $string): string
    {
        /** @var string $result */
        $result = preg_replace('~([A-Z])~', '_$1', $string);

        return mb_strtolower($result);
    }
}
