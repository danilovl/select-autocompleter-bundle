<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Unit\DependencyInjection\Compiler;

use Danilovl\SelectAutocompleterBundle\DependencyInjection\AutocompleterExtension;
use Danilovl\SelectAutocompleterBundle\DependencyInjection\Compiler\AutocompleterCompilerPass;
use Danilovl\SelectAutocompleterBundle\Exception\RuntimeException;
use Danilovl\SelectAutocompleterBundle\Service\AutocompleterContainer;
use Danilovl\SelectAutocompleterBundle\Tests\Mock\Autocompleter\{
    TestAsAutocompleter,
    TestNotExistClassAsAutocompleter
};
use Symfony\Component\Security\Core\Authentication\Token\Storage\{
    TokenStorage,
    TokenStorageInterface
};
use Danilovl\SelectAutocompleterBundle\Tests\Mock\LoadConfigHelper;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Persistence\ManagerRegistry;
use Generator;
use PHPUnit\Framework\Attributes\{
    DataProvider,
    Depends
};
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\{
    Container,
    Definition,
    ContainerBuilder
};
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Environment;

class AutocompleterCompilerPassTest extends TestCase
{
    #[DataProvider('dataProviderHasDefinition')]
    #[Depends('testLoad')]
    public function testCreateDefinitionService(
        string $service,
        bool $expected,
        ContainerBuilder $container
    ): void {
        /** @var AutocompleterContainer $autocompleterContainer */
        $autocompleterContainer = $container->get(AutocompleterContainer::class);

        $this->assertEquals($expected, $autocompleterContainer->has($service));

        if ($autocompleterContainer->has($service)) {
            $autocompleterContainer->get($service);
        }
    }

    #[Depends('testLoad')]
    public function testNotExistClass(ContainerBuilder $container): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Class NotExistClass not found');

        /** @var AutocompleterContainer $autocompleterContainer */
        $autocompleterContainer = $container->get(AutocompleterContainer::class);

        $autocompleterContainer->get('own.not_exist_as_autocompleter_attribute');
    }

    public function testLoad(): ContainerBuilder
    {
        $container = $this->prepareBuilder();

        (new AutocompleterExtension)->load($this->getYamlConfigData(), $container);
        (new AutocompleterCompilerPass)->process($container);

        $this->expectNotToPerformAssertions();

        $container->compile();

        return $container;
    }

    private function prepareBuilder(): ContainerBuilder
    {
        $container = new ContainerBuilder;
        $container->prependExtensionConfig(
            AutocompleterExtension::ALIAS,
            $this->getYamlConfigData()[AutocompleterExtension::ALIAS]
        );
        $container->setParameter('twig.form.resources', []);

        $testAutocompleterAttribute = new Definition(TestAsAutocompleter::class);
        $testAutocompleterAttribute->setPublic(true);
        $testAutocompleterAttribute->setAutowired(true);
        $testAutocompleterAttribute->addTag(AutocompleterCompilerPass::TAGGED_SERVICE_ID_AUTOCOMPLETER);

        $testNotExistClassAutocompleterAttribute = new Definition(TestNotExistClassAsAutocompleter::class);
        $testNotExistClassAutocompleterAttribute->setPublic(true);
        $testNotExistClassAutocompleterAttribute->setAutowired(true);
        $testNotExistClassAutocompleterAttribute->addTag(AutocompleterCompilerPass::TAGGED_SERVICE_ID_AUTOCOMPLETER);

        $autocompleterContainer = new Definition(AutocompleterContainer::class, [new Container]);
        $autocompleterContainer->setPublic(true);

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
            TestAsAutocompleter::class => $testAutocompleterAttribute,
            TestNotExistClassAsAutocompleter::class => $testNotExistClassAutocompleterAttribute,
            AutocompleterContainer::class => $autocompleterContainer,
            TokenStorageInterface::class => $tokenStorage,
            Environment::class => $environment,
            ManagerRegistry::class => $managerRegistry
        ]);

        return $container;
    }

    public static function dataProviderHasDefinition(): Generator
    {
        yield ['orm.not_exist', false];
        yield ['own.as_autocompleter_attribute', true];
    }

    private static function getYamlConfigData(): array
    {
        return LoadConfigHelper::localTestData();
    }
}
