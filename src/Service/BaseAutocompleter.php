<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Service;

use Danilovl\SelectAutocompleterBundle\Exception\{
    NotImplementedMethodException,
    RuntimeException,
    NotImplementedGrantedException
};
use Danilovl\SelectAutocompleterBundle\Interfaces\AutocompleterInterface;
use Danilovl\SelectAutocompleterBundle\Model\Autocompleter\AutocompleterQuery;
use Danilovl\SelectAutocompleterBundle\Model\Config\Config;
use Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat\{
    Item,
    Result
};
use Danilovl\SelectAutocompleterBundle\Resolver\Config\AutocompleterConfigResolver;
use Symfony\Component\Form\ChoiceList\{
    ArrayChoiceList,
    ChoiceListInterface
};

abstract class BaseAutocompleter implements AutocompleterInterface
{
    protected Config $config;

    protected AutocompleterQuery $autocompleterQuery;

    protected bool $isUpdateConfigByResolvedFormType = false;

    public function __construct(protected readonly AutocompleterConfigResolver $resolver) {}

    public function getConfigOptions(): array
    {
        return [];
    }

    public function addConfig(array $options): void
    {
        $autocompleterConfig = array_replace_recursive($options, $this->getConfigOptions());
        $this->config = $this->resolver->resolveConfig($autocompleterConfig);

        if ($this instanceof BaseDoctrineAutocompleter && $this->config->class === null) {
            throw new RuntimeException('You must specify the class for the Doctrine autocompleter.');
        }
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function autocomplete(AutocompleterQuery $query): Result
    {
        throw new NotImplementedMethodException(__METHOD__);
    }

    public function reverseTransform(array $identifiers): array
    {
        throw new NotImplementedMethodException(__METHOD__);
    }

    public function reverseTransformResultIds(array $objects): array
    {
        throw new NotImplementedMethodException(__METHOD__);
    }

    public function transformObjectsToItem(array $objects): array
    {
        return array_map(fn (object $object): Item => $this->transformObjectToItem($object), $objects);
    }

    public function transformObjectToItem(object $object): Item
    {
        return Item::formObject($object, $this->config);
    }

    public function isGranted(): int
    {
        throw new NotImplementedGrantedException('Default isGranted method will be call in AutocompleterService');
    }

    public function isUpdateConfigByResolvedFormType(?bool $isUpdate = null): bool
    {
        $this->isUpdateConfigByResolvedFormType = $isUpdate ?? $this->isUpdateConfigByResolvedFormType;

        return $this->isUpdateConfigByResolvedFormType;
    }

    public function loadChoiceList(?callable $value = null): ChoiceListInterface
    {
        return new ArrayChoiceList([], $value);
    }

    public function loadValuesForChoices(array $choices, ?callable $value = null): array
    {
        return $choices;
    }

    public function loadChoicesForValues(array $values, ?callable $value = null): array
    {
        return $values;
    }
}
