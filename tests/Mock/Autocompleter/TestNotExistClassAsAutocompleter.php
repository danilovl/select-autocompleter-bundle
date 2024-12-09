<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Mock\Autocompleter;

use Danilovl\SelectAutocompleterBundle\Attribute\AsAutocompleter;
use Danilovl\SelectAutocompleterBundle\Service\OrmAutocompleter;

#[AsAutocompleter(alias: 'own.not_exist_as_autocompleter_attribute')]
class TestNotExistClassAsAutocompleter extends OrmAutocompleter
{
    public function getConfigOptions(): array
    {
        return ['class' => 'NotExistClass'];
    }
}
