<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Unit\Model\SelectDataFormat;

use Danilovl\SelectAutocompleterBundle\Model\Config\Config;
use Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat\Item;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    #[DataProvider('dataObject')]
    public function testFormObject(
        object $object,
        Config $config,
        Item $expected
    ): void {
        $item = Item::formObject($object, $config);

        $this->assertEquals($expected, $item);
    }

    public static function dataObject(): Generator
    {
        $defaultConfig = [
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

        $config = Config::fromConfig([
            'id_property' => 'id',
            'to_string' => ['auto' => true],
            ...$defaultConfig
        ]);

        $object = new class() {
            public int $id = 1;

            public function __toString(): string
            {
                return '__toString';
            }
        };

        yield [$object, $config, new Item(1, '__toString')];

        $config = Config::fromConfig([
            'id_property' => 'id',
            'image' => 'image',
            'to_string' => ['auto' => true],
            ...$defaultConfig
        ]);

        $object = new class() {
            public int $id = 1;

            public string $image = 'image';

            public function __toString(): string
            {
                return '__toString';
            }
        };

        yield [$object, $config, new Item(1, '__toString', 'image')];

        $config = Config::fromConfig([
            'id_property' => 'id',
            'to_string' => ['properties' => ['lastName', 'lastname']],
            ...$defaultConfig
        ]);

        $object = new class() {
            public int $id = 1;

            public string $lastName = 'lastName';

            public string $lastname = 'lastname';
        };

        yield [$object, $config, new Item(1, 'lastName lastname')];

        $config = Config::fromConfig([
            'id_property' => 'id',
            'to_string' => ['properties' => ['lastName', 'lastname'], 'format' => '%s - %s'],
            ...$defaultConfig
        ]);

        $object = new class() {
            public int $id = 1;

            public string $lastName = 'lastName';

            public string $lastname = 'lastname';
        };

        yield [$object, $config, new Item(1, 'lastName - lastname')];
    }
}
