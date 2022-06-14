<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Constant;

enum OrderByConstant
{
    final public const ASC = 'ASC';
    final public const DESC = 'DESC';

    final public const TYPES = [
        self::ASC,
        self::DESC
    ];
}
