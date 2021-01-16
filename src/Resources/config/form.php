<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Danilovl\SelectAutocompleterBundle\Form\Type\AutocompleterType;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('danilovl.select_autocompleter.form_type', AutocompleterType::class)
        ->args([
            service('danilovl.select_autocompleter.container'),
            service('danilovl.select_autocompleter.resolver.form.autocompleter_type'),
            service('twig')
        ])
        ->public()
        ->tag('form.type', ['alias' => 'autocompleter'])
        ->alias(AutocompleterType::class, 'danilovl.select_autocompleter.form_type');
};