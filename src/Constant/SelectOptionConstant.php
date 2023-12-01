<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Constant;

enum SelectOptionConstant
{
    final public const string LTR = 'ltr';
    final public const string RTL = 'rtl';

    final public const array DIRS = [
        self::LTR,
        self::RTL
    ];
}
