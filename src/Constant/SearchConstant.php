<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Constant;

class SearchConstant
{
    public const ANY = 'any';
    public const START = 'start';
    public const END = 'end';

    public const TYPES = [
        self::START,
        self::ANY,
        self::END
    ];
}
