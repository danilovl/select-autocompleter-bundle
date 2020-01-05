<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Services\Interfaces;

interface AutocompleterContainerInterface
{
    /**
     * @param string $name
     * @param string $serviceName
     */
    public function addAutocompleter(string $name, string $serviceName): void;

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * @param string $name
     * @return AutocompleterInterface
     */
    public function get(string $name): AutocompleterInterface;
}
