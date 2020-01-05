<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Resolver\Config;

use Danilovl\SelectAutocompleterBundle\Model\Config\ToString;
use Symfony\Component\OptionsResolver\{
    Options,
    OptionsResolver
};

class ToStringResolver
{
    /**
     * @param OptionsResolver $resolver
     * @param ToString $toStringOption
     */
    public function configureOptions(
        OptionsResolver $resolver,
        ToString $toStringOption = null
    ): void {
        $resolver->setDefaults([
            'to_string' => $this->getConfigureOptions($resolver, $toStringOption?? new ToString)
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @param ToString $toStringOption
     * @return callable
     */
    public function getConfigureOptions(
        OptionsResolver $resolver,
        ToString $toStringOption
    ): callable {
        return function (OptionsResolver $resolver) use ($toStringOption): void {
            $resolver
                ->setDefaults([
                    'auto' => $toStringOption->auto,
                    'format' => $toStringOption->format,
                    'properties' => $toStringOption->properties,
                ])
                ->setAllowedTypes('auto', ['bool', 'null'])
                ->setAllowedTypes('format', ['string', 'null'])
                ->setAllowedTypes('properties', ['array', 'null']);
        };
    }
}
