<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Resolver\Form;

use Danilovl\SelectAutocompleterBundle\Model\Form\DependentSelect;
use Symfony\Component\OptionsResolver\{
    Options,
    OptionsResolver
};

class DependentSelectResolver
{
    /**
     * @param OptionsResolver $resolver
     * @param DependentSelect $repository
     */
    public function configureOptions(
        OptionsResolver $resolver,
        DependentSelect $repository
    ): void {
        $resolver->setDefaults([
            'dependent_select' => $this->getConfigureOptions($resolver, $repository)
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @param DependentSelect $dependentSelect
     * @return callable
     */
    public function getConfigureOptions(
        OptionsResolver $resolver,
        DependentSelect $dependentSelect
    ): callable {
        return function (OptionsResolver $resolver) use ($dependentSelect): void {
            $resolver
                ->setDefaults([
                    'name' => $dependentSelect->name,
                    'parent_field' => $dependentSelect->parentField,
                ])
                ->setAllowedTypes('name', ['string', 'null'])
                ->setAllowedTypes('parent_field', ['string', 'null']);
        };
    }
}
