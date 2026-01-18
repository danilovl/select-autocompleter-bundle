<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Resolver\Config;

use Closure;
use Danilovl\SelectAutocompleterBundle\Model\Config\Cdn;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CdnResolver
{
    public function configureOptions(
        OptionsResolver $resolver,
        ?Cdn $cdn = null
    ): void {
        $resolver->setOptions(
            option: 'cdn',
            nested: $this->getConfigureOptions($cdn ?? new Cdn)
        );
    }

    public function getConfigureOptions(Cdn $cdn): Closure
    {
        return static function (OptionsResolver $resolver) use ($cdn): void {
            $resolver
                ->setDefaults([
                    'auto' => $cdn->auto,
                    'link' => $cdn->link,
                    'script' => $cdn->script,
                    'language' => $cdn->language,
                ])
                ->setAllowedTypes('auto', 'bool')
                ->setAllowedTypes('link', ['string', 'null'])
                ->setAllowedTypes('script', ['string', 'null'])
                ->setAllowedTypes('language', ['string', 'null']);
        };
    }
}
