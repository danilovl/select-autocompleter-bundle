<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Form\Loader;

use Danilovl\SelectAutocompleterBundle\Interfaces\AutocompleterInterface;
use Symfony\Component\Form\ChoiceList\Loader\ChoiceLoaderInterface;
use Symfony\Component\Form\ChoiceList\ChoiceListInterface;

readonly class LazyChoiceLoader implements ChoiceLoaderInterface
{
    public function __construct(private AutocompleterInterface $autocompleter) {}

    public function loadChoiceList(callable $value = null): ChoiceListInterface
    {
        return $this->autocompleter->loadChoiceList($value);
    }

    public function loadChoicesForValues(array $values, callable $value = null): array
    {
        return $this->autocompleter->loadChoicesForValues($values, $value);
    }

    public function loadValuesForChoices(array $choices, callable $value = null): array
    {
        return $this->autocompleter->loadValuesForChoices($choices, $value);
    }
}
