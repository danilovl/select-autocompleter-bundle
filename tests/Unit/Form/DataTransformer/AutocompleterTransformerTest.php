<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Unit\Form\DataTransformer;

use Danilovl\SelectAutocompleterBundle\Constant\SecurityConditionConstant;
use Danilovl\SelectAutocompleterBundle\Form\DataTransformer\AutocompleterTransformer;
use Danilovl\SelectAutocompleterBundle\Model\Config\Config;
use Danilovl\SelectAutocompleterBundle\Resolver\Config\AutocompleterConfigResolver;
use Danilovl\SelectAutocompleterBundle\Service\BaseAutocompleter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;

class AutocompleterTransformerTest extends TestCase
{
    private AutocompleterTransformer $transformer;

    protected function setUp(): void
    {
        $defaultValidConfig = [
            'id_property' => 'id',
            'to_string' => ['auto' => true],
            'name' => 'null',
            'root_alias' => 'null',
            'property' => 'null',
            'property_search_type' => 'null',
            'image_result_width' => 'null',
            'image_selection_width' => 'null',
            'limit' => 0,
            'base_template' => 'null',
            'security' => [
                'public_access' => false,
                'voter' => 'voter',
                'condition' => SecurityConditionConstant::AND,
                'role' => []
            ],
            'route' => [
                'name' => null,
                'parameters' => [],
                'extra' => []
            ]
        ];

        $autocompleterConfigResolver = $this->createMock(AutocompleterConfigResolver::class);
        $config = Config::fromConfig($defaultValidConfig);
        $autocompleterConfigResolver->method('resolveConfig')->willReturn($config);

        $autocompleter = new class ($autocompleterConfigResolver) extends BaseAutocompleter {
            public function reverseTransform(array $identifiers): array
            {
                $result = [];

                foreach ($identifiers as $identifier) {
                    $result[] = new class ($identifier) {
                        public function __construct(public int $id) {}
                    };
                }

                return $result;
            }

            public function reverseTransformResultIds(array $objects): array
            {
                return array_map(function (object $object): mixed {
                    return (PropertyAccess::createPropertyAccessor())->getValue($object, $this->getConfig()->idProperty);
                }, $objects);
            }
        };
        $autocompleter->addConfig($defaultValidConfig);

        $this->transformer = new AutocompleterTransformer($autocompleter);
    }

    public function testTransform(): void
    {
        $expected = ['id' => 1, 'text' => 'Some Item', 'image' => null, 'children' => null];
        $item = new class() {
            public int $id = 1;

            public function __toString(): string
            {
                return 'Some Item';
            }
        };

        $result = $this->transformer->transform($item);

        $this->assertEquals($expected, $result);
    }

    public function testReverseTransform(): void
    {
        $expected = new class (1) {
            public function __construct(public int $id) {}
        };

        $result = $this->transformer->reverseTransform(1);

        $this->assertEquals($expected->id, $result->id);
    }
}
