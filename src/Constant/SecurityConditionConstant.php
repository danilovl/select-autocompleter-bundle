<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Constant;

enum SecurityConditionConstant
{
    public const OR = 'or';
    public const AND = 'and';

    public const TYPES = [
        self::OR,
        self::AND
    ];
}
