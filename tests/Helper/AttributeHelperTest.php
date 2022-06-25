<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Helper;

use Danilovl\SelectAutocompleterBundle\Attribute\AsAutocompleter;
use Danilovl\SelectAutocompleterBundle\Helper\AttributeHelper;
use Danilovl\SelectAutocompleterBundle\Tests\Mock\TestAsAutocompleter;
use PHPUnit\Framework\TestCase;

class AttributeHelperTest extends TestCase
{
    public function testGetInstance(): void
    {
        /** @var AsAutocompleter $attribute */
        $attribute = AttributeHelper::getInstance(TestAsAutocompleter::class, AsAutocompleter::class);

        $this->assertEquals('own.as_autocompleter_attribute', $attribute->alias);
    }
}
