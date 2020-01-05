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
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
     */
    private $autocompleters = [];

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $name
     * @param string $serviceName
     */
    public function addAutocompleter(string $name, string $serviceName): void
    {
        if (isset($this->autocompleters[$name])) {
            throw new InvalidArgumentException(sprintf('Autocompleter "%s" is already registered', $name));
        }

        $this->autocompleters[$name] = $serviceName;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->autocompleters[$name]);
    }

    /**
     * @param string $name
     * @return AutocompleterInterface
     */
    public function get(string $name): AutocompleterInterface
    {
        $serviceId = $this->autocompleters[$name] ?? null;
        if ($serviceId !== null) {
            return $this->container->get($serviceId);
        }

        throw new InvalidArgumentException(sprintf('Autocompleter "%s" not registered', $name));
    }
}
