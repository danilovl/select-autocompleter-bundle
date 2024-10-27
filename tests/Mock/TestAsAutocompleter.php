<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Mock;

use Danilovl\SelectAutocompleterBundle\Attribute\AsAutocompleter;
use Danilovl\SelectAutocompleterBundle\Service\OrmAutocompleter;

#[AsAutocompleter(alias: 'own.as_autocompleter_attribute')]
class TestAsAutocompleter extends OrmAutocompleter {}
