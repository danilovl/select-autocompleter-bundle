<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Danilovl\SelectAutocompleterBundle\Form\Type\AutocompleterType;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set(AutocompleterType::class, AutocompleterType::class)
        ->autowire()
        ->public()
        ->tag('form.type', ['alias' => 'autocompleter']);
};
