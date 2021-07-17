<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\DependencyInjection\Compiler;

use Danilovl\SelectAutocompleterBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\Processor;

class AutocompleterCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder): void
    {
        $configs = $containerBuilder->getExtensionConfig('danilovl_select_autocompleter');
        $configuration = new Configuration;
        $config = $this->processConfiguration($configuration, $configs);

        $autocompleterContainer = $containerBuilder->getDefinition('danilovl.select_autocompleter.container');
        $taggedServices = $containerBuilder->findTaggedServiceIds('danilovl.select_autocompleter.autocompleter');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                if (!isset($attributes['alias'])) {
                    continue;
                }

                $alias = $attributes['alias'];
                $aliasType = explode('.', $alias);

                $defaultAutocompleterConfig = $config['default_option'];
                foreach ($config[$aliasType[0]] as $autocompleterConfig) {
                    if ($autocompleterConfig['name'] === $aliasType[1]) {
                        $defaultAutocompleterConfig = array_replace_recursive($defaultAutocompleterConfig, $autocompleterConfig);
                    }
                }

                $customAutocompleter = $containerBuilder->getDefinition($id);
                $customAutocompleter->addMethodCall('addConfig', [$defaultAutocompleterConfig]);

                $autocompleterContainer->addMethodCall('replaceAutocompleter', [$attributes['alias'], $id]);
            }
        }
    }

    private function processConfiguration(ConfigurationInterface $configuration, array $configs): array
    {
        return (new Processor)->processConfiguration($configuration, $configs);
    }
}
