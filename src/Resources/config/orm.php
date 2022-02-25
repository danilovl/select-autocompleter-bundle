<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Danilovl\SelectAutocompleterBundle\Service\OrmAutocompleter;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set(OrmAutocompleter::class, OrmAutocompleter::class)
        ->autowire()
        ->public()
        ->abstract();
};
