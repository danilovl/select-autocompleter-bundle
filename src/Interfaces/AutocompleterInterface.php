<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Interfaces;

use Danilovl\SelectAutocompleterBundle\Model\Autocompleter\AutocompleterQuery;
use Danilovl\SelectAutocompleterBundle\Model\Config\Config;
use Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat\{
    Item,
    Result
};
use Symfony\Component\Form\ChoiceList\ChoiceListInterface;

interface AutocompleterInterface
{
    public function autocomplete(AutocompleterQuery $query): Result;

    public function getConfig(): Config;

    /**
     * @param array<string, mixed> $options
     */
    public function addConfig(array $options): void;

    /**
     * @return Item[]
     */
    public function transformObjectsToItem(array $objects): array;

    public function transformObjectToItem(object $object): Item;

    public function reverseTransform(array $identifiers): array;

    public function reverseTransformResultIds(array $objects): array;

    public function isGranted(): int;

    public function isUpdateConfigByResolvedFormType(?bool $isUpdate = null): bool;

    public function loadChoiceList(?callable $value = null): ChoiceListInterface;

    public function loadChoicesForValues(array $values, ?callable $value = null): array;

    public function loadValuesForChoices(array $choices, ?callable $value = null): array;
}
