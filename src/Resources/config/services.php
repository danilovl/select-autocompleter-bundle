<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Danilovl\SelectAutocompleterBundle\Services\{
    AutocompleterService,
    AutocompleterContainer
};
use Danilovl\SelectAutocompleterBundle\Controller\AutocompleterController;
use Danilovl\SelectAutocompleterBundle\Form\Type\AutocompleterType;
use Danilovl\SelectAutocompleterBundle\Proxy\AutocompleterResolvedFormTypeFactory;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('danilovl.select_autocompleter.container', AutocompleterContainer::class)
        ->args([
            service('service_container')
        ])
        ->private()
        ->alias(AutocompleterContainer::class, 'danilovl.select_autocompleter.container');

    $container->services()
        ->set('danilovl.select_autocompleter.autocompleter', AutocompleterService::class)
        ->args([
            service('service_container'),
            service('danilovl.select_autocompleter.container'),
            service('security.token_storage')
        ])
        ->public()
        ->alias(AutocompleterService::class, 'danilovl.select_autocompleter.autocompleter');

    $container->services()
        ->set('danilovl.select_autocompleter.controller', AutocompleterController::class)
        ->args([
            service('danilovl.select_autocompleter.autocompleter')
        ])
        ->public()
        ->alias(AutocompleterController::class, 'danilovl.select_autocompleter.controller');

    $container->services()
        ->set('form.resolved_type_factory', AutocompleterResolvedFormTypeFactory::class);
};