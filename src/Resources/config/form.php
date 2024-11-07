<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Danilovl\SelectAutocompleterBundle\Form\Type\{
    AutocompleterType,
    MultipleAutocompleterType
};

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set(AutocompleterType::class, AutocompleterType::class)
        ->autowire()
        ->public()
        ->tag('form.type', ['alias' => 'autocompleter']);

    $container->services()
        ->set(MultipleAutocompleterType::class, MultipleAutocompleterType::class)
        ->autowire()
        ->public()
        ->tag('form.type', ['alias' => 'autocompleter']);
};
