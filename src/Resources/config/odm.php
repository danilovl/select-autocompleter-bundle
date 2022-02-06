<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Danilovl\SelectAutocompleterBundle\Service\OdmAutocompleter;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set(OdmAutocompleter::class, OdmAutocompleter::class)
        ->autowire()
        ->public()
        ->abstract();
};