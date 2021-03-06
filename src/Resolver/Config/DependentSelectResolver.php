<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Resolver\Config;

use Danilovl\SelectAutocompleterBundle\Model\Config\DependentSelect;
use Symfony\Component\OptionsResolver\{
    Options,
    OptionsResolver
};

class DependentSelectResolver
{
    public function configureOptions(
        OptionsResolver $resolver,
        DependentSelect $repository
    ): void {
        $resolver->setDefaults([
            'dependent_select' => $this->getConfigureOptions($resolver, $repository)
        ]);
    }

    public function getConfigureOptions(
        OptionsResolver $resolver,
        DependentSelect $dependentSelect
    ): callable {
        return static function (OptionsResolver $resolver) use ($dependentSelect): void {
            $resolver
                ->setDefaults([
                    'parent_property' => $dependentSelect->parentProperty,
                    'parent_field' => $dependentSelect->parentField,
                    'many_to_many' => $dependentSelect->manyToMany
                ])
                ->setAllowedTypes('parent_property', ['string', 'null'])
                ->setAllowedTypes('parent_field', ['string', 'null'])
                ->setAllowedTypes('many_to_many', ['array', 'null']);
        };
    }
}
