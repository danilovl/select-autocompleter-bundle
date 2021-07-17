<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Resolver\Config;

use Danilovl\SelectAutocompleterBundle\Model\Config\Security;
use Symfony\Component\OptionsResolver\{
    Options,
    OptionsResolver
};

class SecurityResolver
{
    public function configureOptions(
        OptionsResolver $resolver,
        Security $security = null
    ): void {
        $resolver->setDefaults([
            'security' => $this->getConfigureOptions($resolver, $security ?? new Security)
        ]);
    }

    public function getConfigureOptions(
        OptionsResolver $resolver,
        Security $security
    ): callable {
        return static function (OptionsResolver $resolver) use ($security): void {
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
