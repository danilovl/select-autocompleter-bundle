<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Constant;

enum SecurityConditionConstant
{
    final public const string OR = 'or';
    final public const string AND = 'and';

    final public const array TYPES = [
        self::OR,
        self::AND
    ];
}
