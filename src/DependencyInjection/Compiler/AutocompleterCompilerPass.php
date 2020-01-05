<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AutocompleterCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $containerBuilder
     */
    public function process(ContainerBuilder $containerBuilder): void
    {
        $autocompleterContainer = $containerBuilder->getDefinition('danilovl_select_autocompleter.container');
        $taggedServices = $containerBuilder->findTaggedServiceIds('danilovl_select_autocompleter.autocompleter');

        foreach ($taggedServices as $id => $tags) {
            $autocompleterContainer->addMethodCall('addAutocompleter', [$containerBuilder->getDefinition($id)->getClass(), $id]);

            foreach ($tags as $attributes) {
                if (!isset($attributes['alias'])) {
                    continue;
                }

                $autocompleterContainer->addMethodCall('addAutocompleter', [$attributes['alias'], $id]);
            }
        }
    }
}
