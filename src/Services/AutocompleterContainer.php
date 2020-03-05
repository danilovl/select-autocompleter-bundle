<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Services;

use Danilovl\SelectAutocompleterBundle\Services\Interfaces\{
    AutocompleterInterface,
    AutocompleterContainerInterface
};
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AutocompleterContainer implements AutocompleterContainerInterface
{
	private ContainerInterface $container;
	private array $autocompleters = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function addAutocompleter(string $name, string $serviceName): void
    {
        if (isset($this->autocompleters[$name])) {
            throw new InvalidArgumentException(sprintf('Autocompleter "%s" is already registered', $name));
        }

        $this->autocompleters[$name] = $serviceName;
    }

    public function replaceAutocompleter(string $name, string $serviceName): void
    {
        if (!isset($this->autocompleters[$name])) {
            throw new InvalidArgumentException(sprintf('Autocompleter "%s" is not yet registered', $name));
        }

        $this->autocompleters[$name] = $serviceName;
    }

    public function has(string $name): bool
    {
        return isset($this->autocompleters[$name]);
    }

    public function get(string $name): AutocompleterInterface
    {
        $serviceId = $this->autocompleters[$name] ?? null;
        if ($serviceId !== null) {
            return $this->container->get($serviceId);
        }

        throw new InvalidArgumentException(sprintf('Autocompleter "%s" not registered', $name));
    }
}
