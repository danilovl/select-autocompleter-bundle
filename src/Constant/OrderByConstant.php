<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Constant;

enum OrderByConstant
{
    public const ASC = 'ASC';
    public const DESC = 'DESC';

    public const TYPES = [
        self::ASC,
        self::DESC
    ];
}
