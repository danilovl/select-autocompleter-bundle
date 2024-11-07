<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Constant;

use Danilovl\SelectAutocompleterBundle\Service\{
    OdmAutocompleter,
    OrmAutocompleter
};

enum ServiceConstant
{
    final public const string ORM = 'orm';
    final public const string ODM = 'odm';
    final public const string OWN = 'own';
    final public const string SERVICE_FORMAT = 'danilovl.select_autocompleter.%s.%s';
    final public const string PARENT_SERVICE_ORM = OrmAutocompleter::class;
    final public const string PARENT_SERVICE_ODM = OdmAutocompleter::class;
}
