<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Unit\Resolver\Form;

use Danilovl\SelectAutocompleterBundle\Model\Form\DependentSelect;
use Danilovl\SelectAutocompleterBundle\Resolver\Form\DependentSelectResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DependentSelectResolverTest extends TestCase
{
    private DependentSelectResolver $resolver;

    protected function setUp(): void
    {
        $this->resolver = new DependentSelectResolver;
    }

    public function testConfigureOptionsWithMultipleDependentSelects(): void
    {
        $optionsResolver = new OptionsResolver;
        $this->resolver->configureOptions($optionsResolver, new DependentSelect);

        $input = [
            'dependent_selects' => [
                [
                    'name' => 'first',
                    'parent_field' => 'parent_first'
                ],
                [
                    'name' => 'second',
                    'parent_field' => 'parent_second'
                ]
            ]
        ];

        $resolved = $optionsResolver->resolve($input);

        $this->assertCount(2, $resolved['dependent_selects']);
        $this->assertEquals('first', $resolved['dependent_selects'][0]['name']);
        $this->assertEquals('parent_first', $resolved['dependent_selects'][0]['parent_field']);
        $this->assertEquals('second', $resolved['dependent_selects'][1]['name']);
        $this->assertEquals('parent_second', $resolved['dependent_selects'][1]['parent_field']);
    }

    public function testConfigureOptionsWithEmptyArray(): void
    {
        $optionsResolver = new OptionsResolver;
        $this->resolver->configureOptions($optionsResolver, new DependentSelect);

        $input = [
            'dependent_selects' => []
        ];

        $resolved = $optionsResolver->resolve($input);

        $this->assertIsArray($resolved['dependent_selects']);
        $this->assertEmpty($resolved['dependent_selects']);
    }

    public function testConfigureOptionsDefaults(): void
    {
        $optionsResolver = new OptionsResolver;
        $this->resolver->configureOptions($optionsResolver, new DependentSelect);

        $input = [
            'dependent_selects' => [
                []
            ]
        ];

        $resolved = $optionsResolver->resolve($input);

        $this->assertCount(1, $resolved['dependent_selects']);
        $this->assertNull($resolved['dependent_selects'][0]['name']);
        $this->assertNull($resolved['dependent_selects'][0]['parent_field']);
    }
}
