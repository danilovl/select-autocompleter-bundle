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
    /**
     * @param AutocompleterQuery $query
     * @return Result
     */
    public function autocomplete(AutocompleterQuery $query): Result;
 
    /**
     * @return Config
     */
    public function getConfig(): Config;

    /**
     * @param array $objects
     * @return Item[]
     */
    public function transformObjectsToItem(array $objects): array;

    /**
     * @param $object
     * @return Item
     */
    public function transformObjectToItem($object): Item;

    /**
     * @param array $ids
     * @return array
     */
    public function reverseTransform(array $ids): array;
}
