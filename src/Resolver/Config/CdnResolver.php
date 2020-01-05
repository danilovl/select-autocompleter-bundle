<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Resolver\Config;

use Danilovl\SelectAutocompleterBundle\Model\Config\Cdn;
use Symfony\Component\OptionsResolver\{
    Options,
    OptionsResolver
};

class CdnResolver
{
    /**
     * @param OptionsResolver $resolver
     * @param Cdn $cdn
     */
    public function configureOptions(
        OptionsResolver $resolver,
        Cdn $cdn = null
    ): void {
        $resolver->setDefaults([
            'cdn' => $this->getConfigureOptions($resolver, $cdn ??  new Cdn)
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @param Cdn $cdn
     * @return callable
     */
    public function getConfigureOptions(
        OptionsResolver $resolver,
        Cdn $cdn
    ): callable {
        return function (OptionsResolver $resolver) use ($cdn): void {
            $resolver
                ->setDefaults([
                    'auto' => $cdn->auto,
                    'link' => $cdn->link,
                    'script' => $cdn->script,
                ])
                ->setAllowedTypes('auto', 'bool')
                ->setAllowedTypes('link', ['string', 'null'])
                ->setAllowedTypes('script', ['string', 'null']);
        };
    }
}
