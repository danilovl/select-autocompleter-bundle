<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Constant;

enum SecurityConditionConstant
{
    final public const OR = 'or';
    final public const AND = 'and';

    final public const TYPES = [
        self::OR,
        self::AND
    ];
}
