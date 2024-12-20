<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\DependencyInjection\Compiler;

use Danilovl\SelectAutocompleterBundle\DependencyInjection\{
    Configuration,
    AutocompleterExtension
};
use Danilovl\SelectAutocompleterBundle\Attribute\AsAutocompleter;
use Danilovl\SelectAutocompleterBundle\Helper\AttributeHelper;
use Danilovl\SelectAutocompleterBundle\Interfaces\AutocompleterInterface;
use Danilovl\SelectAutocompleterBundle\Service\AutocompleterContainer;
use LogicException;
use RuntimeException;
use Symfony\Component\Config\Definition\{
    Processor,
    ConfigurationInterface
};
use Symfony\Component\DependencyInjection\{
    Definition,
    ContainerBuilder
};
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class AutocompleterCompilerPass implements CompilerPassInterface
{
    final public const string TAGGED_SERVICE_ID_AUTOCOMPLETER = 'danilovl.select_autocompleter.autocompleter';

    public function process(ContainerBuilder $container): void
    {
        $configs = $container->getExtensionConfig(AutocompleterExtension::ALIAS);
        $configuration = new Configuration;
        $config = $this->processConfiguration($configuration, $configs);

        $autocompleterContainer = $container->getDefinition(AutocompleterContainer::class);
        $taggedServices = $container->findTaggedServiceIds(self::TAGGED_SERVICE_ID_AUTOCOMPLETER, true);

        foreach ($taggedServices as $id => $tags) {
            $customAutocompleter = $container->getDefinition($id);

            $alias = $this->getAlias($id, $customAutocompleter, $tags);
            if (empty($alias)) {
                continue;
            }

            $aliasData = explode('.', $alias);
            $aliasType = $aliasData[0];
            $aliasName = $aliasData[1];

            $defaultAutocompleterConfig = $config['default_option'];

            foreach ($config[$aliasType] as $autocompleterConfig) {
                if ($autocompleterConfig['name'] === $aliasName) {
                    $defaultAutocompleterConfig = array_replace_recursive($defaultAutocompleterConfig, $autocompleterConfig);
                }
            }

            $customAutocompleter->setPublic(true);
            /** @var string $class */
            $class = $customAutocompleter->getClass();

            if (!is_subclass_of($class, AutocompleterInterface::class)) {
                throw new RuntimeException(sprintf('Autocompleter must implement interface %s', AutocompleterInterface::class));
            }

            if (!isset($defaultAutocompleterConfig['name'])) {
                $defaultAutocompleterConfig['name'] = $aliasName;
            }

            $customAutocompleter->addMethodCall('addConfig', [$defaultAutocompleterConfig]);
            $autocompleterContainer->addMethodCall('replaceAutocompleter', [$alias, $id]);
        }
    }

    private function getAlias(string $serviceId, Definition $serviceDefinition, array $tags): string
    {
        $alias = $tags[0]['alias'] ?? null;
        if ($alias !== null) {
            return $alias;
        }

        /** @var string $class */
        $class = $serviceDefinition->getClass();
        /** @var AsAutocompleter|null $attribute */
        $attribute = AttributeHelper::getInstance($class, AsAutocompleter::class);

        if ($attribute === null) {
            throw new LogicException(sprintf('The service "%s" needs to implement attribute "%s".', $serviceId, AsAutocompleter::class));
        }

        return $attribute->alias;
    }

    private function processConfiguration(ConfigurationInterface $configuration, array $configs): array
    {
        return (new Processor)->processConfiguration($configuration, $configs);
    }
}
