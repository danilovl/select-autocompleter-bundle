<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Danilovl\SelectAutocompleterBundle\Service\{
    AutocompleterService,
    AutocompleterContainer
};
use Danilovl\SelectAutocompleterBundle\Controller\AutocompleterController;
use Danilovl\SelectAutocompleterBundle\Form\Loader\LazyChoiceLoaderFactory;
use Danilovl\SelectAutocompleterBundle\Interfaces\AutocompleterContainerInterface;
use Danilovl\SelectAutocompleterBundle\Proxy\AutocompleterResolvedFormTypeFactory;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->public();

    $services
        ->set(AutocompleterContainer::class, AutocompleterContainer::class)
        ->arg('$container', service('service_container'))
        ->alias(AutocompleterContainerInterface::class, AutocompleterContainer::class);

    $services
        ->set(AutocompleterService::class, AutocompleterService::class)
        ->arg('$container', service('service_container'));

    $services
        ->set(AutocompleterController::class, AutocompleterController::class)
        ->public()
        ->autowire();

    $services
        ->set(LazyChoiceLoaderFactory::class, LazyChoiceLoaderFactory::class)
        ->public()
        ->autowire();

    $services->set('form.resolved_type_factory', AutocompleterResolvedFormTypeFactory::class);
};
