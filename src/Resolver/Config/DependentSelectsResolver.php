<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Resolver\Config;

use Danilovl\SelectAutocompleterBundle\Model\Config\DependentSelects;
use Symfony\Component\OptionsResolver\{
    Options,
    OptionsResolver
};

class DependentSelectsResolver
{
    /**
     * @param OptionsResolver $resolver
     * @param DependentSelects $dependentSelects
     */
    public function configureOptions(
        OptionsResolver $resolver,
        DependentSelects $dependentSelects = null
    ): void {
        $resolver->setDefaults([
            'dependent_selects' => [
                $this->getConfigureOptions($resolver, $dependentSelects ?? new DependentSelects)
            ]
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @param DependentSelects $dependentSelects
     * @return callable
     */
    public function getConfigureOptions(
        OptionsResolver $resolver,
        DependentSelects $dependentSelects
    ): callable {
        return function (OptionsResolver $resolver) use ($dependentSelects): void {
            $resolver
                ->setDefaults([
                    'parent_property' => $dependentSelects->parentProperty,
                    'parent_field' => $dependentSelects->parentField,
                    'many_to_many' => $dependentSelects->manyToMany
                ])
                ->setAllowedTypes('parent_property', ['string', 'null'])
                ->setAllowedTypes('parent_field', ['string', 'null'])
                ->setAllowedTypes('many_to_many', ['array', 'null']);
        };
    }
}
