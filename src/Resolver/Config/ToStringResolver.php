<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Resolver\Config;

use Closure;
use Danilovl\SelectAutocompleterBundle\Model\Config\ToString;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ToStringResolver
{
    public function configureOptions(
        OptionsResolver $resolver,
        ?ToString $toStringOption = null
    ): void {
        $resolver->setOptions(
            option: 'to_string',
            nested: $this->getConfigureOptions($toStringOption ?? new ToString)
        );
    }

    public function getConfigureOptions(ToString $toStringOption): Closure
    {
        return static function (OptionsResolver $resolver) use ($toStringOption): void {
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
