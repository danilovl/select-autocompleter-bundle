<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Unit\Model\Config;

use Danilovl\SelectAutocompleterBundle\Model\Config\Config;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use TypeError;

class ConfigTest extends TestCase
{
    #[DataProvider('dataSuccess')]
    public function testDataSuccess(array $config): void
    {
        Config::fromConfig($config);

        $this->assertTrue(true);
    }

    #[DataProvider('dataFailed')]
    public function testDataFailed(array $config): void
    {
        $this->expectException(TypeError::class);

        Config::fromConfig($config);
    }

    public static function dataSuccess(): Generator
    {
        $config = [
            'id_property' => 'id',
            'name' => 'null',
            'root_alias' => 'null',
            'property' => 'null',
            'property_search_type' => 'null',
            'image_result_width' => 'null',
            'image_selection_width' => 'null',
            'limit' => 0,
            'base_template' => 'null',
            'security' => [
                'public_access' => true,
                'voter' => 'voter',
                'condition' => 'condition',
                'role' => []
            ],
            'route' => [
                'name' => null,
                'parameters' => [],
                'extra' => []
            ]
        ];

        yield [$config];

        $config = [
            'id_property' => 'id',
            'name' => 'null',
            'root_alias' => 'null',
            'property' => 'null',
            'property_search_type' => 'null',
            'image_result_width' => 'null',
            'image_selection_width' => 'null',
            'limit' => 0,
            'base_template' => 'null',
            'excluded_entity_id' => [],
            'search_simple' => [],
            'search_pattern' => [],
            'order_by' => [],
            'where' => [],
            'widget' => 'null',
            'security' => [
                'public_access' => true,
                'voter' => 'voter',
                'condition' => 'condition',
                'role' => []
            ],
            'route' => [
                'name' => null,
                'parameters' => [],
                'extra' => []
            ]
        ];

        yield [$config];
    }

    public static function dataFailed(): Generator
    {
        $defaultSuccessConfig = [
            'id_property' => 'id',
            'name' => 'null',
            'root_alias' => 'null',
            'property' => 'null',
            'property_search_type' => 'null',
            'image_result_width' => 'null',
            'image_selection_width' => 'null',
            'limit' => 0,
            'base_template' => 'null',
            'security' => [
                'public_access' => true,
                'voter' => 'voter',
                'condition' => 'condition',
                'role' => []
            ],
            'route' => [
                'name' => null,
                'parameters' => [],
                'extra' => []
            ]
        ];

        $configs = [
            ['name' => null],
            ['where' => 1],
            ['class' => 1],
            ['root_alias' => 1],
            ['root_alias' => []],
            ['manager' => []],
            ['base_template' => []],
            ['base_template' => 1],
            ['id_property' => []],
            ['id_property' => 1],
            ['image' => 1],
            ['excluded_entity_id' => 1],
            ['search_simple' => 1],
            ['search_simple' => '1'],
            ['order_by' => 1],
            ['order_by' => '1'],
            ['where' => '1'],
            ['where' => 1],
            ['to_string' => 1],
            ['cdn' => 1],
            ['select_option' => 1],
            ['security' => 1],
            ['security' => '[]'],
            ['repository' => 1],
            ['route' => 1],
            ['route' => '[]'],
            ['dependent_selects' => 1]
        ];

        foreach ($configs as $config) {
            $config = [...$defaultSuccessConfig, ...$config];
            yield [$config];
        }
    }
}
