<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Resolver\Form;

use Danilovl\SelectAutocompleterBundle\Model\Config\SelectOption;
use Danilovl\SelectAutocompleterBundle\Resolver\Config\{
    CdnResolver,
    ToStringResolver,
    SelectOptionResolver
};
use Danilovl\SelectAutocompleterBundle\Model\Form\DependentSelect;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\ChoiceList\{
    EntityLoaderInterface,
    ORMQueryBuilderLoader
};
use Symfony\Component\OptionsResolver\{
    Options,
    OptionsResolver
};

class AutocompleterTypeResolver
{
    public function __construct(
        private SelectOptionResolver $selectOptionResolver,
        private CdnResolver $cdnResolver,
        private ToStringResolver $toStringResolver,
        private DependentSelectResolver $dependentSelectResolver
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $defaults = [
            'compound' => false,
            'autocompleter' => function (OptionsResolver $resolver): void {
                $resolver
                    ->setDefaults([
                        'name' => null,
                        'multiple' => false,
                        'widget' => null,
                        'base_template' => null,
                        'extra' => [],
                    ])
                    ->setRequired('name')
                    ->setAllowedTypes('name', 'string')
                    ->setAllowedTypes('multiple', 'bool')
                    ->setAllowedTypes('widget', ['string', 'null'])
                    ->setAllowedTypes('base_template', ['string', 'null'])
                    ->setAllowedTypes('extra', 'array');

                $this->selectOptionResolver->configureOptions($resolver, new SelectOption);
                $this->dependentSelectResolver->configureOptions($resolver, new DependentSelect);

                $resolver->setAllowedTypes('select_option', ['array', 'null']);
                $resolver->setAllowedTypes('dependent_select', ['array', 'null']);
            }
        ];

        $resolver->setDefaults($defaults)
            ->setRequired('autocompleter')
            ->setAllowedTypes('compound', 'bool')
            ->setAllowedTypes('autocompleter', ['array', 'null']);
    }
}
