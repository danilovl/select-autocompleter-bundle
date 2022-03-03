<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Service;

use Danilovl\SelectAutocompleterBundle\Interfaces\AutocompleterInterface;
use Danilovl\SelectAutocompleterBundle\Model\Autocompleter\AutocompleterQuery;
use Danilovl\SelectAutocompleterBundle\Model\Config\Config;
use Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat\{
    Item,
    Result
};
use Danilovl\SelectAutocompleterBundle\Resolver\Config\AutocompleterConfigResolver;
use RuntimeException;
use Symfony\Component\OptionsResolver\Options;

abstract class BaseAutocompleter implements AutocompleterInterface
{
    protected ?Config $config = null;
    protected ?Options $options = null;
    protected ?AutocompleterQuery $autocompleterQuery = null;

    public function __construct(protected AutocompleterConfigResolver $resolver)
    {
    }

    public function addConfig(array $options): void
    {
        $this->config = $this->resolver->resolveConfig($options);
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function autocomplete(AutocompleterQuery $query): Result
    {
        throw new RuntimeException('Need implement logic');
    }

    public function reverseTransform(array $identifiers): array
    {
        throw new RuntimeException('Need implement logic');
    }

    public function transformObjectsToItem(array $objects): array
    {
        return array_map(fn(object $object): Item => $this->transformObjectToItem($object), $objects);
    }

    public function transformObjectToItem(object $object): Item
    {
        return Item::formObject($object, $this->config);
    }
}