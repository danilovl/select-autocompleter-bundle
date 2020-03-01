<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Proxy;

use Symfony\Component\Form\FormFactoryInterface;

class AutocompeleterResolvedFormType extends \Symfony\Component\Form\ResolvedFormType
{
    public function createBuilder(FormFactoryInterface $factory, string $name, array $options = [])
    {
        $builder = parent::createBuilder($factory, $name, $options);

        $builder->setAttribute('autocompleter/passed_options', $options);
        $builder->setType($this);

        return $builder;
    }
}
