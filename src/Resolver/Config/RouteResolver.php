<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Resolver\Config;

use Danilovl\SelectAutocompleterBundle\Model\Config\Route;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RouteResolver
{
    public function configureOptions(
        OptionsResolver $resolver,
        Route $route = null
    ): void {
        $resolver->setDefaults([
            'route' => $this->getConfigureOptions($route ?? new Route)
        ]);
    }

    public function getConfigureOptions(Route $route): callable
    {
        return static function (OptionsResolver $resolver) use ($route): void {
            $resolver
                ->setDefaults([
                    'name' => $route->name,
                    'parameters' => $route->parameters,
                    'extra' => $route->extra,
                ])
                ->setAllowedTypes('name', ['string', 'null'])
                ->setAllowedTypes('parameters', ['array'])
                ->setAllowedTypes('extra', ['array']);
        };
    }
}
