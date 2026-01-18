<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Resolver\Config;

use Closure;
use Danilovl\SelectAutocompleterBundle\Model\Config\Security;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SecurityResolver
{
    public function configureOptions(
        OptionsResolver $resolver,
        ?Security $security = null
    ): void {
        $resolver->setOptions(
            option: 'security',
            nested: $this->getConfigureOptions($security ?? new Security)
        );
    }

    public function getConfigureOptions(Security $security): Closure
    {
        return static function (OptionsResolver $resolver) use ($security): void {
            $resolver
                ->setDefaults([
                    'voter' => $security->voter,
                    'role' => $security->role,
                    'condition' => $security->condition,
                    'public_access' => $security->publicAccess,
                ])
                ->setAllowedTypes('voter', ['string', 'null'])
                ->setAllowedTypes('role', ['array'])
                ->setAllowedTypes('condition', ['string'])
                ->setAllowedTypes('public_access', ['bool']);
        };
    }
}
