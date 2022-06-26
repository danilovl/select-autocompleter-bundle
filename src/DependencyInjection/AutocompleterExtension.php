<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\{
    ChildDefinition,
    ContainerBuilder,
    Definition
};
use Danilovl\SelectAutocompleterBundle\Attribute\AsAutocompleter;
use Danilovl\SelectAutocompleterBundle\Constant\ServiceConstant;
use Danilovl\SelectAutocompleterBundle\DependencyInjection\Compiler\AutocompleterCompilerPass;
use Danilovl\SelectAutocompleterBundle\Service\AutocompleterContainer;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class AutocompleterExtension extends Extension
{
    final public const ALIAS = 'danilovl_select_autocompleter';
    private const DIR_CONFIG = '/../Resources/config';

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration;
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . self::DIR_CONFIG));
        $loader->load('services.yaml');
        $loader->load('resolvers.yaml');
        $loader->load('form.yaml');
        $loader->load('voter.yaml');

        $this->registerAttribute($container);
        $this->processConfigurationAutocompleters($container, $loader, $config);
        $this->addingFormResources($container);
    }

    private function registerAttribute(ContainerBuilder $container): void
    {
        $container->registerAttributeForAutoconfiguration(AsAutocompleter::class, static function (Definition $definition): void {
            $definition->addTag(AutocompleterCompilerPass::TAGGED_SERVICE_ID_AUTOCOMPLETER);
        });
    }

    private function processConfigurationAutocompleters(
        ContainerBuilder $container,
        YamlFileLoader $loader,
        array $config
    ): void {
        foreach ($config as $key => $parameters) {
            $parentService = match ($key) {
                ServiceConstant::ORM => ServiceConstant::PARENT_SERVICE_ORM,
                ServiceConstant::ODM => ServiceConstant::PARENT_SERVICE_ODM,
                default => null
            };

            if ($parentService === null || empty($parameters)) {
                continue;
            }

            $loader->load("{$key}.yaml");
            $this->createAutocompleterService(
                $container,
                $config['default_option'],
                $parameters,
                $parentService,
                $key
            );
        }
    }

    private function addingFormResources(ContainerBuilder $container): void
    {
        $container->setParameter(
            'twig.form.resources',
            array_merge(
                ['@SelectAutocompleter/Form/select_autocompleter.html.twig'],
                $container->getParameter('twig.form.resources')
            )
        );
    }

    private function createAutocompleterService(
        ContainerBuilder $containerBuilder,
        array $defaultConfig,
        array $autocompleters,
        string $parentService,
        string $type
    ): void {
        $autocompleterContainer = $containerBuilder->getDefinition(AutocompleterContainer::class);
        foreach ($autocompleters as $autocompleterConfig) {
            $autocompleterConfig = array_replace_recursive($defaultConfig, $autocompleterConfig);

            $definition = new ChildDefinition($parentService);
            $definition->setPublic(true);
            $definition->addMethodCall('addConfig', [$autocompleterConfig]);

            $name = $autocompleterConfig['name'];
            $serviceId = sprintf(ServiceConstant::SERVICE_FORMAT, $type, $name);
            $autocompleterId = sprintf('%s.%s', $type, $autocompleterConfig['name']);

            $autocompleterContainer->addMethodCall('addAutocompleter', [$autocompleterId, $serviceId]);
            $containerBuilder->setDefinition($serviceId, $definition);
        }
    }

    public function getAlias(): string
    {
        return self::ALIAS;
    }
}
