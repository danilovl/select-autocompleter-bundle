<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Constant;

enum SelectOptionConstant
{
    final public const LTR = 'ltr';
    final public const RTL = 'rtl';

    final public const DIRS = [
        self::LTR,
        self::RTL
    ];
}
