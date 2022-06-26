<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\DependencyInjection;

use Danilovl\SelectAutocompleterBundle\Service\AutocompleterContainer;
use Danilovl\SelectAutocompleterBundle\Tests\Mock\LoadConfigHelper;
use PHPUnit\Framework\TestCase;
use Danilovl\SelectAutocompleterBundle\DependencyInjection\{
    Configuration,
    AutocompleterExtension
};
use Generator;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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

        (new AutocompleterExtension)->load(
            $this->getYamlConfigData(),
            $container
        );

        $this->expectNotToPerformAssertions();

        return $container;
    }

    /**
     * @dataProvider dataProviderHasDefinition
     * @depends testLoad
     */
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
    }

    private function prepareBuilder(): ContainerBuilder
    {
        $container = new ContainerBuilder;
        $container->setParameter('twig.form.resources', []);

        return $container;
    }

    public function dataProviderHasDefinition(): Generator
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