<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Mock\Autocompleter;

use Danilovl\SelectAutocompleterBundle\Service\OdmAutocompleter;

class TestOdmAutocompleter extends OdmAutocompleter
{
    public function createSearchSimplePatternPublic(string $search, string $searchType): string
    {
        return $this->createSearchSimplePattern($search, $searchType);
    }

    public function createSearchPatternPublic(string $search, string $pattern): string
    {
        return $this->createSearchPattern($search, $pattern);
    }
}
