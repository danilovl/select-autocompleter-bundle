<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Danilovl\SelectAutocompleterBundle\Services\OdmAutocompleter;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('danilovl.select_autocompleter.odm', OdmAutocompleter::class)
        ->args([
            service('doctrine_mongodb'),
            service('danilovl.select_autocompleter.resolver.config')
        ])
        ->abstract()
        ->alias(OdmAutocompleter::class, 'danilovl.select_autocompleter.odm');
};