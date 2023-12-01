<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Constant;

enum OrderByConstant
{
    final public const string ASC = 'ASC';
    final public const string DESC = 'DESC';

    final public const array TYPES = [
        self::ASC,
        self::DESC
    ];
}
