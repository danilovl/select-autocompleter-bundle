<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Mock\Autocompleter;

use Danilovl\SelectAutocompleterBundle\Attribute\AsAutocompleter;
use Danilovl\SelectAutocompleterBundle\Service\OrmAutocompleter;
use Danilovl\SelectAutocompleterBundle\Tests\Mock\Entity\Product;

#[AsAutocompleter(alias: 'own.as_autocompleter_attribute')]
class TestAsAutocompleter extends OrmAutocompleter
{
    public function getConfigOptions(): array
    {
        return ['class' => Product::class];
    }
}
