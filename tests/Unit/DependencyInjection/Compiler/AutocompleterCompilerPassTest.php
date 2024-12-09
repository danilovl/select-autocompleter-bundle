<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Unit\DependencyInjection\Compiler;

use Danilovl\SelectAutocompleterBundle\DependencyInjection\AutocompleterExtension;
use Danilovl\SelectAutocompleterBundle\DependencyInjection\Compiler\AutocompleterCompilerPass;
use Danilovl\SelectAutocompleterBundle\Service\AutocompleterContainer;
use Danilovl\SelectAutocompleterBundle\Tests\Mock\{
    Autocompleter\TestAsAutocompleter,
    LoadConfigHelper};
use Generator;
use PHPUnit\Framework\Attributes\{
    DataProvider,
    Depends};
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\{
    Container,
    ContainerBuilder,
    Definition};

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
    }

    public function testLoad(): ContainerBuilder
    {
        $container = $this->prepareBuilder();
        (new AutocompleterCompilerPass)->process($container);

        $this->expectNotToPerformAssertions();

        return $container;
    }

    private function prepareBuilder(): ContainerBuilder
    {
        $container = new ContainerBuilder;
        $container->prependExtensionConfig(
            AutocompleterExtension::ALIAS,
            $this->getYamlConfigData()[AutocompleterExtension::ALIAS]
        );

        $testAutocompleterAttribute = new Definition(TestAsAutocompleter::class);
        $testAutocompleterAttribute->addTag(AutocompleterCompilerPass::TAGGED_SERVICE_ID_AUTOCOMPLETER);

        $container->addDefinitions([
            TestAsAutocompleter::class => $testAutocompleterAttribute,
            AutocompleterContainer::class => new Definition(AutocompleterContainer::class, [
                new Container
            ])
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
