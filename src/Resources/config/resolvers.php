<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Danilovl\SelectAutocompleterBundle\Resolver\Config\{
    CdnResolver,
    SecurityResolver,
    ToStringResolver,
    RepositoryResolver,
    SelectOptionResolver,
    DependentSelectsResolver,
    AutocompleterConfigResolver
};
use Danilovl\SelectAutocompleterBundle\Resolver\Form\{
    DependentSelectResolver,
    AutocompleterTypeResolver
};

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('danilovl.select_autocompleter.resolver.config', AutocompleterConfigResolver::class)
        ->args([
            service('danilovl.select_autocompleter.resolver.config.attr'),
            service('danilovl.select_autocompleter.resolver.config.cdn'),
            service('danilovl.select_autocompleter.resolver.config.to_string'),
            service('danilovl.select_autocompleter.resolver.config.security'),
            service('danilovl.select_autocompleter.resolver.config.repository'),
            service('danilovl.select_autocompleter.resolver.config.dependent_selects')
        ])
        ->public()
        ->alias(AutocompleterConfigResolver::class, 'danilovl.select_autocompleter.resolver.config');

    $container->services()
        ->set('danilovl.select_autocompleter.resolver.config.attr', SelectOptionResolver::class)
        ->public()
        ->alias(SelectOptionResolver::class, 'danilovl.select_autocompleter.resolver.config.attr');

    $container->services()
        ->set('danilovl.select_autocompleter.resolver.config.cdn', CdnResolver::class)
        ->public()
        ->alias(CdnResolver::class, 'danilovl.select_autocompleter.resolver.config.cdn');

    $container->services()
        ->set('danilovl.select_autocompleter.resolver.config.security', SecurityResolver::class)
        ->public()
        ->alias(SecurityResolver::class, 'danilovl.select_autocompleter.resolver.config.security');

    $container->services()
        ->set('danilovl.select_autocompleter.resolver.config.to_string', ToStringResolver::class)
        ->public()
        ->alias(ToStringResolver::class, 'danilovl.select_autocompleter.resolver.config.to_string');

    $container->services()
        ->set('danilovl.select_autocompleter.resolver.config.repository', RepositoryResolver::class)
        ->public()
        ->alias(RepositoryResolver::class, 'danilovl.select_autocompleter.resolver.config.repository');

    $container->services()
        ->set('danilovl.select_autocompleter.resolver.config.dependent_selects', DependentSelectsResolver::class)
        ->public()
        ->alias(DependentSelectsResolver::class, 'danilovl.select_autocompleter.resolver.config.dependent_selects');

    $container->services()
        ->set('danilovl.select_autocompleter.resolver.form.dependent_select', DependentSelectResolver::class)
        ->public()
        ->alias(DependentSelectResolver::class, 'danilovl.select_autocompleter.resolver.form.dependent_select');

    $container->services()
        ->set('danilovl.select_autocompleter.resolver.form.autocompleter_type', AutocompleterTypeResolver::class)
        ->args([
            service('danilovl.select_autocompleter.resolver.config.attr'),
            service('danilovl.select_autocompleter.resolver.config.cdn'),
            service('danilovl.select_autocompleter.resolver.config.to_string'),
            service('danilovl.select_autocompleter.resolver.form.dependent_select')
        ])
        ->public()
        ->alias(AutocompleterTypeResolver::class, 'danilovl.select_autocompleter.resolver.form.autocompleter_type');
};