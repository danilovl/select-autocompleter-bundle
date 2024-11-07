<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Proxy;

use Danilovl\SelectAutocompleterBundle\Interfaces\AutocompleterContainerInterface;
use Symfony\Component\Form\{
    FormTypeInterface,
    ResolvedFormTypeFactory,
    ResolvedFormTypeInterface
};

class AutocompleterResolvedFormTypeFactory extends ResolvedFormTypeFactory
{
    public function __construct(private readonly AutocompleterContainerInterface $autocompleterContainer) {}

    public function createResolvedType(
        FormTypeInterface $type,
        array $typeExtensions,
        ResolvedFormTypeInterface $parent = null
    ): ResolvedFormTypeInterface {
        return new AutocompeleterResolvedFormType(
            $this->autocompleterContainer,
            $type,
            $typeExtensions,
            $parent
        );
    }
}
