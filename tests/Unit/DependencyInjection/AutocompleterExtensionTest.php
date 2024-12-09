<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Unit\DependencyInjection;

use Danilovl\SelectAutocompleterBundle\DependencyInjection\{
    Configuration,
    AutocompleterExtension
};
use Danilovl\SelectAutocompleterBundle\Service\AutocompleterContainer;
use Danilovl\SelectAutocompleterBundle\Tests\Mock\LoadConfigHelper;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Persistence\ManagerRegistry;
use Generator;
use PHPUnit\Framework\Attributes\{
    Depends,
    DataProvider
};
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\{
    Container,
    Definition,
    ContainerBuilder
};
use Symfony\Component\Security\Core\Authentication\Token\Storage\{
    TokenStorage,
    TokenStorageInterface
};
use Twig\Environment;

class AutocompleterExtensionTest extends TestCase
{
    public function testConfiguration(): void
    {
        $configuration = new Configuration;
        $node = $configuration->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize($this->getYamlConfigData()[AutocompleterExtension::ALIAS]);
        $node->finalize($normalizedConfig);

        $this->expectNotToPerformAssertions();
    }

    public function testLoad(): ContainerBuilder
    {
        $container = $this->prepareBuilder();
        (new AutocompleterExtension)->load($this->getYamlConfigData(), $container);

        $this->expectNotToPerformAssertions();

        $container->compile();

        return $container;
    }

    #[DataProvider('dataProviderHasDefinition')]
    #[Depends('testLoad')]
    public function testCreateDefinitionService(
        string $service,
        bool $expected,
        ContainerBuilder $container
    ): void {
        $autocompleterContainer = $container->getDefinition(AutocompleterContainer::class);
        $autocompleterContainer->setClass(AutocompleterContainer::class);

        /** @var AutocompleterContainer $autocompleterContainer */
        $autocompleterContainer = $container->get(AutocompleterContainer::class);

        $this->assertEquals($expected, $autocompleterContainer->has($service));

        if ($autocompleterContainer->has($service)) {
            $autocompleterContainer->get($service);
        }
    }

    private function prepareBuilder(): ContainerBuilder
    {
        $container = new ContainerBuilder;
        $container->setParameter('twig.form.resources', []);

        $security = new Definition(Security::class, [new Container]);
        $security->setPublic(true);

        $tokenStorage = new Definition(TokenStorage::class);
        $tokenStorage->setPublic(true);

        $environment = new Definition(Environment::class);
        $environment->setPublic(true);

        $managerRegistry = new Definition(Registry::class, [new Container, [], [], 'default', 'default']);
        $managerRegistry->setPublic(true);

        $container->addDefinitions([
            'security.helper' => $security,
            TokenStorageInterface::class => $tokenStorage,
            Environment::class => $environment,
            ManagerRegistry::class => $managerRegistry
        ]);

        return $container;
    }

    public static function dataProviderHasDefinition(): Generator
    {
        yield ['orm.shop', true];
        yield ['orm.product', true];
        yield ['orm.not_exist', false];
    }

    private function getYamlConfigData(): array
    {
        return LoadConfigHelper::localTestData();
    }
}
