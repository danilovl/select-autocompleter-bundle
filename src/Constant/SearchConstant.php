<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Constant;

enum SearchConstant
{
    final public const string ANY = 'any';
    final public const string START = 'start';
    final public const string END = 'end';
    final public const string EQUAL = 'equal';

    final public const array TYPES = [
        self::START,
        self::ANY,
        self::END,
        self::EQUAL
    ];
}
