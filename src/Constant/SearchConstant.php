<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Constant;

enum SearchConstant
{
    final public const ANY = 'any';
    final public const START = 'start';
    final public const END = 'end';
    final public const EQUAL = 'equal';

    final public const TYPES = [
        self::START,
        self::ANY,
        self::END,
        self::EQUAL
    ];
}
