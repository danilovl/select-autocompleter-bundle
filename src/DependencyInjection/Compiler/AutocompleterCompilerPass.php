<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\DependencyInjection\Compiler;

use Danilovl\SelectAutocompleterBundle\DependencyInjection\Configuration;
use Danilovl\SelectAutocompleterBundle\Interfaces\AutocompleterInterface;
use Danilovl\SelectAutocompleterBundle\Service\AutocompleterContainer;
use RuntimeException;
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

        $autocompleterContainer = $containerBuilder->getDefinition(AutocompleterContainer::class);
        $taggedServices = $containerBuilder->findTaggedServiceIds('danilovl.select_autocompleter.autocompleter');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                if (!isset($attributes['alias'])) {
                    continue;
                }

                $alias = $attributes['alias'];
                $aliasData = explode('.', $alias);
                $aliasType = $aliasData[0];
                $aliasName = $aliasData[1];

                $defaultAutocompleterConfig = $config['default_option'];

                foreach ($config[$aliasType] as $autocompleterConfig) {
                    if ($autocompleterConfig['name'] === $aliasName) {
                        $defaultAutocompleterConfig = array_replace_recursive($defaultAutocompleterConfig, $autocompleterConfig);
                    }
                }

                $customAutocompleter = $containerBuilder->getDefinition($id);
                $customAutocompleter->setPublic(true);

                if (!is_subclass_of($customAutocompleter->getClass(), AutocompleterInterface::class)) {
                    throw new RuntimeException(sprintf('Autocompleter must implement interface %s', AutocompleterInterface::class));
                }

                if (!isset($defaultAutocompleterConfig['name'])) {
                    $defaultAutocompleterConfig['name'] = $aliasName;
                }

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
