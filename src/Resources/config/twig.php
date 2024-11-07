<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Danilovl\SelectAutocompleterBundle\Twig\RenderBlockControlTwigExtension;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->public();

    $services
        ->set(RenderBlockControlTwigExtension::class, RenderBlockControlTwigExtension::class)
        ->tag('twig.extension');
};
