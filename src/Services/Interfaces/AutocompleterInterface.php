<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Services\Interfaces;

use Danilovl\SelectAutocompleterBundle\Model\Autocompleter\AutocompleterQuery;
use Danilovl\SelectAutocompleterBundle\Model\Config\Config;
use Danilovl\SelectAutocompleterBundle\Model\Config\DefaultOption;
use Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat\{
    Item,
    Result
};

interface AutocompleterInterface
{
    public function autocomplete(AutocompleterQuery $query): Result;

    public function getConfig(): Config;

    public function transformObjectsToItem(array $objects): array;

    public function transformObjectToItem(object $object): Item;

    public function reverseTransform(array $ids): array;
}
