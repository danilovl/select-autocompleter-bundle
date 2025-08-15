<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Unit\Model\SelectDataFormat;

use Danilovl\SelectAutocompleterBundle\Exception\RuntimeException;
use Danilovl\SelectAutocompleterBundle\Model\Config\Config;
use Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat\{
    Item,
    ItemChildren
};
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

class ItemTest extends TestCase
{
    public function testItemWithoutChildrenAndWithId(): void
    {
        $item = new Item(id: 1, text: 'Test item');

        $this->assertEquals(1, $item->id);
        $this->assertEquals('Test item', $item->text);
        $this->assertNull($item->image);
        $this->assertNull($item->children);
    }

    public function testItemWithChildrenAndWithoutId(): void
    {
        $childItem = new ItemChildren(id: 1, text: 'Child item');
        $item = new Item(id: null, text: 'Parent item', children: [$childItem]);

        $this->assertNull($item->id);
        $this->assertEquals('Parent item', $item->text);
        $this->assertNull($item->image);
        $this->assertIsArray($item->children);
        $this->assertCount(1, $item->children);
        $this->assertSame($childItem, $item->children[0]);
    }

    public function testItemThrowsExceptionIfNoChildrenAndNoId(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('If the item has no children, it must have an id');

        new Item(id: null, text: 'Invalid item');
    }

    public function testItemThrowsExceptionIfHasChildrenAndId(): void
    {
        $childItem = new ItemChildren(id: 1, text: 'Child item');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('If the item has children, it must not have an id');

        new Item(id: 1, text: 'Invalid parent item', children: [$childItem]);
    }

    public function testItemThrowsExceptionIfChildrenAreNotInstanceOfItemChildren(): void
    {
        /** @var ItemChildren $invalidChild */
        $invalidChild = new stdClass;

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('All children must be instances of ItemChildren');

        new Item(id: null, text: 'Parent item', children: [$invalidChild]);
    }

    public function testItemAllowsChildrenThatAreInstanceOfItemChildren(): void
    {
        $childItem1 = new ItemChildren(id: 1, text: 'Child item');
        $childItem2 = new ItemChildren(id: 2, text: 'Child item');

        $item = new Item(id: null, text: 'Valid parent item', children: [$childItem1, $childItem2]);
        /** @var array $children */
        $children = $item->children;

        $this->assertNull($item->id);
        $this->assertEquals('Valid parent item', $item->text);
        $this->assertCount(2, $children);
        $this->assertSame($childItem1, $children[0]);
        $this->assertSame($childItem2, $children[1]);
    }

    #[DataProvider('provideFormObjectCases')]
    public function testFormObject(
        object $object,
        Config $config,
        Item $expected
    ): void {
        $item = Item::formObject($object, $config);

        $this->assertEquals($expected, $item);
    }

    public static function provideFormObjectCases(): Generator
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
