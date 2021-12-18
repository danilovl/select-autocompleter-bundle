<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Proxy;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\ResolvedFormType;

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
