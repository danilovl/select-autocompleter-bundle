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

        $this->assertIsArray($result['dependent_selects']);
        $this->assertIsArray($result['dependent_selects'][0]);
        $this->assertEquals('child_field', $result['dependent_selects'][0]['name']);
        $this->assertEquals('parent_field', $result['dependent_selects'][0]['parent_field']);
    }

    public function testNested(): void
    {
        $child = new DependentSelects;
        $child->name = 'nested';

        $object = new class([[$child]]) {
            public function __construct(public array $nested) {}
        };

        $result = ArrayHelper::modelToArray($object);

        $this->assertIsArray($result['nested'][0][0]);
        $this->assertEquals('nested', $result['nested'][0][0]['name']);
    }
}
