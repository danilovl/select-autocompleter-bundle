<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Resolver\Form;

use Closure;
use Danilovl\SelectAutocompleterBundle\Model\Config\Route;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RouteResolver
{
    public function configureOptions(
        OptionsResolver $resolver,
        Route $route
    ): void {
        $resolver->setOptions(
            option: 'route',
            nested: $this->getConfigureOptions($route)
        );
    }

    public function getConfigureOptions(Route $route): Closure
    {
        return static function (OptionsResolver $resolver) use ($route): void {
            $resolver
                ->setDefaults([
                    'extra' => $route->extra
                ])
                ->setAllowedTypes('extra', ['array']);
        };
    }
}
