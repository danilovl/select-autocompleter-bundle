<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Resolver\Form;

use Danilovl\SelectAutocompleterBundle\Model\Config\{
    Route,
    SelectOption
};
use Danilovl\SelectAutocompleterBundle\Resolver\Config\SelectOptionResolver;
use Danilovl\SelectAutocompleterBundle\Model\Form\DependentSelect;
use Symfony\Component\OptionsResolver\OptionsResolver;

readonly class AutocompleterTypeResolver
{
    public function __construct(
        private SelectOptionResolver $selectOptionResolver,
        private DependentSelectResolver $dependentSelectResolver,
        private RouteResolver $routeResolver
    ) {}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $defaults = [
            'compound' => false
        ];

        $resolver->setOptions('autocompleter', function (OptionsResolver $resolver): void {
            $resolver
                ->setDefaults([
                    'name' => null,
                    'widget' => null,
                    'base_template' => null
                ])
                ->setRequired('name')
                ->setAllowedTypes('name', 'string')
                ->setAllowedTypes('widget', ['string', 'null'])
                ->setAllowedTypes('base_template', ['string', 'null']);

            $this->selectOptionResolver->configureOptions($resolver, new SelectOption);
            $this->dependentSelectResolver->configureOptions($resolver, new DependentSelect);
            $this->routeResolver->configureOptions($resolver, new Route);

            $resolver->setAllowedTypes('select_option', ['array', 'null']);
            $resolver->setAllowedTypes('dependent_select', ['array', 'null']);
        });


        $resolver->setDefaults($defaults)
            ->setRequired('autocompleter')
            ->setAllowedTypes('compound', 'bool')
            ->setAllowedTypes('autocompleter', ['array', 'null']);
    }
}
