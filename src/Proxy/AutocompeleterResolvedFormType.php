<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Proxy;

use Symfony\Component\Form\{
    ResolvedFormType,
    FormBuilderInterface,
    FormFactoryInterface
};

class AutocompeleterResolvedFormType extends ResolvedFormType
{
    public function createBuilder(FormFactoryInterface $factory, string $name, array $options = []): FormBuilderInterface
    {
        $builder = parent::createBuilder($factory, $name, $options);

        $builder->setAttribute('autocompleter/passed_options', $options);
        $builder->setType($this);

        return $builder;
    }
}
