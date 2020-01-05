<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Resolver\Config;

use Danilovl\SelectAutocompleterBundle\Model\Config\Security;
use Symfony\Component\OptionsResolver\{
    Options,
    OptionsResolver
};

class SecurityResolver
{
    /**
     * @param OptionsResolver $resolver
     * @param Security $security
     */
    public function configureOptions(
        OptionsResolver $resolver,
        Security $security = null
    ): void {
        $resolver->setDefaults([
            'security' => $this->getConfigureOptions($resolver, $security ?? new Security)
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @param Security $security
     * @return callable
     */
    public function getConfigureOptions(
        OptionsResolver $resolver,
        Security $security
    ): callable {
        return function (OptionsResolver $resolver) use ($security): void {
            $resolver
                ->setDefaults([
                    'voter' => $security->voter,
                    'role' => $security->role,
                ])
                ->setAllowedTypes('voter', ['string', 'null'])
                ->setAllowedTypes('role', ['array', 'null']);
        };
    }
}
