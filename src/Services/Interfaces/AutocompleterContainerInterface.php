<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Services\Interfaces;

interface AutocompleterContainerInterface
{
    public function addAutocompleter(string $name, string $serviceName): void;

    public function replaceAutocompleter(string $name, string $serviceName): void;

    public function has(string $name): bool;

    public function get(string $name): AutocompleterInterface;
}
