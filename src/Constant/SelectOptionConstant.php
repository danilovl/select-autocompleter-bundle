<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Constant;

enum SelectOptionConstant
{
    public const LTR = 'ltr';
    public const RTL = 'rtl';

    public const DIRS = [
        self::LTR,
        self::RTL
    ];
}
