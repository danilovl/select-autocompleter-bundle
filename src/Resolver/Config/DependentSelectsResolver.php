<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Resolver\Config;

use Symfony\Component\OptionsResolver\{
    Options,
    OptionsResolver
};

class DependentSelectsResolver
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('dependent_selects', [])
            ->setAllowedTypes('dependent_selects', 'array')
            ->setNormalizer('dependent_selects', function (Options $options, array $value): array {
                $resolved = [];

                foreach ($value as $item) {
                    $resolver = new OptionsResolver;
                    $this->configureDependentOptions($resolver);
                    $resolved[] = $resolver->resolve($item);
                }

                return $resolved;
            });
    }

    private function configureDependentOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'name' => null,
                'parent_property' => null,
                'parent_field' => null,
                'many_to_many' => []
            ])
            ->setAllowedTypes('name', ['string', 'null'])
            ->setAllowedTypes('parent_property', ['string', 'null'])
            ->setAllowedTypes('parent_field', ['string', 'null'])
            ->setAllowedTypes('many_to_many', ['array', 'null']);
    }
}
