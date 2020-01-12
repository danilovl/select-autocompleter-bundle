<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\DependencyInjection\Compiler;

use Danilovl\SelectAutocompleterBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\Processor;

class AutocompleterCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $containerBuilder
     */
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

                foreach ($config[$aliasType[0]] as $autocompleterConfig) {
                    if ($autocompleterConfig['name'] === $aliasType[1]) {
                        $autocompleterConfig = array_replace_recursive($config['default_option'], $autocompleterConfig);
                    }
                }

                $customAutocompleter = $containerBuilder->getDefinition($id);
                $customAutocompleter->addMethodCall('addConfig', [$autocompleterConfig]);

                $autocompleterContainer->addMethodCall('replaceAutocompleter', [$attributes['alias'], $id]);
            }
        }
    }

    /**
     * @param ConfigurationInterface $configuration
     * @param array $configs
     * @return array
     */
    private function processConfiguration(ConfigurationInterface $configuration, array $configs): array
    {
        return (new Processor)->processConfiguration($configuration, $configs);
    }
}
