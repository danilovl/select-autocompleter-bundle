<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Resolver\Form;

use Closure;
use Danilovl\SelectAutocompleterBundle\Model\Form\DependentSelect;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DependentSelectResolver
{
    public function configureOptions(
        OptionsResolver $resolver,
        DependentSelect $repository
    ): void {
        $resolver->setOptions(
            option: 'dependent_select',
            nested: $this->getConfigureOptions($repository)
        );
    }

    public function getConfigureOptions(DependentSelect $dependentSelect): Closure
    {
        return static function (OptionsResolver $resolver) use ($dependentSelect): void {
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
