<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Form\Loader;

use Danilovl\SelectAutocompleterBundle\Interfaces\AutocompleterContainerInterface;

readonly class LazyChoiceLoaderFactory
{
    public function __construct(private AutocompleterContainerInterface $autocompleterContainer) {}

    public function createLazyChoiceLoader(string $autocompleter): LazyChoiceLoader
    {
        return new LazyChoiceLoader($this->autocompleterContainer->get($autocompleter));
    }
}
