<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Unit\Helper;

use Danilovl\SelectAutocompleterBundle\Helper\ArrayHelper;
use Danilovl\SelectAutocompleterBundle\Model\Config\DependentSelects;
use PHPUnit\Framework\TestCase;

class ArrayHelperTest extends TestCase
{
    public function testWithDependentSelects(): void
    {
        $dependentSelect = new DependentSelects;
        $dependentSelect->name = 'child_field';
        $dependentSelect->parentField = 'parent_field';

        $object = new class([$dependentSelect]) {
            public function __construct(public array $dependentSelects) {}
        };

        $result = ArrayHelper::modelToArray($object);

        $expected = [
            'dependent_selects' => [
                [
                    'name' => 'child_field',
                    'parent_property' => null,
                    'parent_field' => 'parent_field',
                    'many_to_many' => []
                ]
            ]
        ];

        $this->assertSame($expected, $result);
    }

    public function testNested(): void
    {
        $child = new DependentSelects;
        $child->name = 'nested';

        $object = new class([[$child]]) {
            public function __construct(public array $nested) {}
        };

        $result = ArrayHelper::modelToArray($object);

        $expected = [
            'nested' => [
                [
                    [
                        'name' => 'nested',
                        'parent_property' => null,
                        'parent_field' => null,
                        'many_to_many' => []
                    ]
                ]
            ]
        ];

        $this->assertSame($expected, $result);
    }
}
