<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Resolver\Config;

use Closure;
use Danilovl\SelectAutocompleterBundle\Model\Config\Repository;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepositoryResolver
{
    public function configureOptions(
        OptionsResolver $resolver,
        ?Repository $repository = null
    ): void {
        $resolver->setOptions(
            option: 'repository',
            nested: $this->getConfigureOptions($repository ?? new Repository)
        );
    }

    public function getConfigureOptions(Repository $repository): Closure
    {
        return static function (OptionsResolver $resolver) use ($repository): void {
            $resolver
                ->setDefaults([
                    'method' => $repository->method,
                ])
                ->setAllowedTypes('method', ['string', 'null']);
        };
    }
}
