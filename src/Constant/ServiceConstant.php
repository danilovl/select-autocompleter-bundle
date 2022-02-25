<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Constant;

use Danilovl\SelectAutocompleterBundle\Service\{
    OdmAutocompleter,
    OrmAutocompleter
};

enum ServiceConstant
{
    public const ORM = 'orm';
    public const ODM = 'orm';
    public const OWN = 'own';
    public const SERVICE_FORMAT = 'danilovl.select_autocompleter.%s.%s';
    public const PARENT_SERVICE_ORM = OrmAutocompleter::class;
    public const PARENT_SERVICE_ODM = OdmAutocompleter::class;
}
