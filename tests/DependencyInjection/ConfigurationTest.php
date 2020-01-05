<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests;

use Danilovl\SelectAutocompleterBundle\DependencyInjection\{
    Configuration,
    AutocompleterExtension
};
use Generator;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\{
    ContainerBuilder,
    ContainerInterface
};
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;

class ConfigurationTest extends KernelTestCase
{
    /**
     * @return void
     */
    public function testConfiguration(): void
    {
        $configuration = new Configuration;
        $node = $configuration->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize($this->getYamlConfigData());
        $node->finalize($normalizedConfig);

        $this->expectNotToPerformAssertions();
    }

    /**
     * @return ContainerBuilder
     */
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
     * @param string $service
     * @param bool $expected
     * @param ContainerBuilder $container
     */
    public function testCreateDefinitionService(
        string $service,
        bool $expected,
        ContainerBuilder $container
    ) {
        $this->assertEquals($expected, $container->hasDefinition($service));
    }

    /**
     * @return ContainerBuilder
     */
    private function prepareBuilder(): ContainerBuilder
    {
        $container = new ContainerBuilder;
        $container->setParameter('twig.form.resources', []);

        return $container;
    }

    /**
     * @return Generator
     */
    public function dataProviderHasDefinition(): Generator
    {
        yield ['danilovl_select_autocompleter.orm.shop', true];
        yield ['danilovl_select_autocompleter.orm.product', true];
        yield ['danilovl_select_autocompleter.orm.not_exist', false];
    }

    /**
     * @return array
     */
    private function getYamlConfigData(): array
    {
        return Yaml::parseFile(__DIR__ . DIRECTORY_SEPARATOR . 'test.yaml');
    }
}