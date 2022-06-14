<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Constant;

use Danilovl\SelectAutocompleterBundle\Service\{
    OdmAutocompleter,
    OrmAutocompleter
};

enum ServiceConstant
{
    final public const ORM = 'orm';
    final public const ODM = 'orm';
    final public const OWN = 'own';
    final public const SERVICE_FORMAT = 'danilovl.select_autocompleter.%s.%s';
    final public const PARENT_SERVICE_ORM = OrmAutocompleter::class;
    final public const PARENT_SERVICE_ODM = OdmAutocompleter::class;
}
