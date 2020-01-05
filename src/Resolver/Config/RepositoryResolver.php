<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Resolver\Config;

use Danilovl\SelectAutocompleterBundle\Model\Config\Repository;
use Symfony\Component\OptionsResolver\{
    Options,
    OptionsResolver
};

class RepositoryResolver
{
    /**
     * @param OptionsResolver $resolver
     * @param Repository $repository
     */
    public function configureOptions(
        OptionsResolver $resolver,
        Repository $repository = null
    ): void {
        $resolver->setDefaults([
            'repository' => $this->getConfigureOptions($resolver, $repository ?? new Repository)
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @param Repository $repository
     * @return callable
     */
    public function getConfigureOptions(
        OptionsResolver $resolver,
        Repository $repository
    ): callable {
        return function (OptionsResolver $resolver) use ($repository): void {
            $resolver
                ->setDefaults([
                    'method' => $repository->method,
                ])
                ->setAllowedTypes('method', ['string', 'null']);
        };
    }
}
