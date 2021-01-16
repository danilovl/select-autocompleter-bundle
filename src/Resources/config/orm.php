<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Danilovl\SelectAutocompleterBundle\Services\OrmAutocompleter;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('danilovl.select_autocompleter.orm', OrmAutocompleter::class)
        ->args([
            service('doctrine'),
            service('danilovl.select_autocompleter.resolver.config')
        ])
        ->abstract()
        ->alias(OrmAutocompleter::class, 'danilovl.select_autocompleter.orm');
};