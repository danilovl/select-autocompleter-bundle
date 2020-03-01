<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Proxy;

use Symfony\Component\Form\{
    FormTypeInterface,
    ResolvedFormTypeFactory,
    ResolvedFormTypeInterface
};

class AutocompleterResolvedFormTypeFactory extends ResolvedFormTypeFactory
{
    public function createResolvedType(
        FormTypeInterface $type,
        array $typeExtensions,
        ResolvedFormTypeInterface $parent = null
    ) {
        return new AutocompeleterResolvedFormType($type, $typeExtensions, $parent);
    }
}
