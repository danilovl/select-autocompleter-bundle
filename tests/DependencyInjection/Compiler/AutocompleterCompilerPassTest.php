<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\DependencyInjection\Compiler;

use Danilovl\SelectAutocompleterBundle\Service\AutocompleterContainer;
use Danilovl\SelectAutocompleterBundle\Tests\Mock\{
    LoadConfigHelper,
    TestAsAutocompleter
};
use PHPUnit\Framework\TestCase;
use Danilovl\SelectAutocompleterBundle\DependencyInjection\AutocompleterExtension;
use Danilovl\SelectAutocompleterBundle\DependencyInjection\Compiler\AutocompleterCompilerPass;
use Generator;
use Symfony\Component\DependencyInjection\{
    Container,
    Definition,
    ContainerBuilder
};

class AutocompleterCompilerPassTest extends TestCase
{
    /**
     * @dataProvider dataProviderHasDefinition
     * @depends      testLoad
     */
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
        (new AutocompleterCompilerPass())->process($container);

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
                new Container()
            ])
        ]);

        return $container;
    }

    public function dataProviderHasDefinition(): Generator
    {
        yield ['orm.not_exist', false];
        yield ['own.as_autocompleter_attribute', true];
    }

    private function getYamlConfigData(): array
    {
        return LoadConfigHelper::localTestData();
    }
}
