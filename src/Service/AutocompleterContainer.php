<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Service;

use Danilovl\SelectAutocompleterBundle\Interfaces\{
    AutocompleterInterface,
    AutocompleterContainerInterface
};
use Danilovl\SelectAutocompleterBundle\Exception\{
    NotAutocompleterException,
    AutocompleterNotExitException
};
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AutocompleterContainer implements AutocompleterContainerInterface
{
    /**
     * @var array<string, string>
     */
    private array $autocompleters = [];

    public function __construct(private readonly ContainerInterface $container) {}

    public function addAutocompleter(string $name, string $serviceName): void
    {
        if (isset($this->autocompleters[$name])) {
            throw new InvalidArgumentException(sprintf('Autocompleter "%s" is already registered', $name));
        }

        $this->autocompleters[$name] = $serviceName;
    }

    public function replaceAutocompleter(string $name, string $serviceName): void
    {
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
            /** @var object|AutocompleterInterface|null $service */
            $service = $this->container->get($serviceId);
            if ($service === null) {
                throw new AutocompleterNotExitException(sprintf('Service with name "%s" does not exist.', $serviceId));
            }

            if (!$service instanceof AutocompleterInterface) {
                $message = sprintf('Service name "%s" does not implement "%s" interface. It should not be in the autocmpleter container.',
                    $serviceId,
                    AutocompleterInterface::class
                );

                throw new NotAutocompleterException($message);
            }

            return $service;
        }

        throw new InvalidArgumentException(sprintf('Autocompleter "%s" not registered', $name));
    }
}
