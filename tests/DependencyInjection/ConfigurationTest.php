<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests;

use Danilovl\SelectAutocompleterBundle\DependencyInjection\{
    Configuration,
    AutocompleterExtension
};
use Generator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

class ConfigurationTest extends KernelTestCase
{
    public function testConfiguration(): void
    {
        $configuration = new Configuration;
        $node = $configuration->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize($this->getYamlConfigData()['danilovl_select_autocompleter']);
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
        $this->assertEquals($expected, $container->hasDefinition($service));
    }

    private function prepareBuilder(): ContainerBuilder
    {
        $container = new ContainerBuilder;
        $container->setParameter('twig.form.resources', []);

        return $container;
    }

    public function dataProviderHasDefinition(): Generator
    {
        yield ['danilovl.select_autocompleter.orm.shop', true];
        yield ['danilovl.select_autocompleter.orm.product', true];
        yield ['danilovl.select_autocompleter.orm.not_exist', false];
    }

    private function getYamlConfigData(): array
    {
        return Yaml::parseFile(__DIR__ . DIRECTORY_SEPARATOR . 'test.yaml');
    }
}
