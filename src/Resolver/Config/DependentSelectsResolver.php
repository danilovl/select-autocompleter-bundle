<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Resolver\Config;

use Danilovl\SelectAutocompleterBundle\Model\Config\DependentSelects;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DependentSelectsResolver
{
    public function configureOptions(
        OptionsResolver $resolver,
        ?DependentSelects $dependentSelects = null
    ): void {
        $resolver->setDefaults([
            'dependent_selects' => [
                $this->getConfigureOptions($dependentSelects ?? new DependentSelects)
            ]
        ]);
    }

    public function getConfigureOptions(DependentSelects $dependentSelects): callable
    {
        return static function (OptionsResolver $resolver) use ($dependentSelects): void {
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
