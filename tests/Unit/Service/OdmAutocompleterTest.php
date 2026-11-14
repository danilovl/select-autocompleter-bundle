<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Unit\Service;

use Danilovl\SelectAutocompleterBundle\Resolver\Config\AutocompleterConfigResolver;
use Danilovl\SelectAutocompleterBundle\Tests\Mock\Autocompleter\TestOdmAutocompleter;
use Doctrine\Persistence\ManagerRegistry;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class OdmAutocompleterTest extends TestCase
{
    private TestOdmAutocompleter $autocompleter;

    protected function setUp(): void
    {
        $this->autocompleter = new TestOdmAutocompleter(
            $this->createStub(ManagerRegistry::class),
            $this->createStub(AutocompleterConfigResolver::class)
        );
    }

    #[DataProvider('provideSearchSimplePatternCases')]
    public function testSearchSimplePattern(string $searchType, string $expected): void
    {
        $pattern = $this->autocompleter->createSearchSimplePatternPublic('abc', $searchType);

        $this->assertSame($expected, $pattern);
    }

    #[DataProvider('provideSearchSimplePatternMatchesCases')]
    public function testSearchSimplePatternMatches(string $searchType, string $value, bool $expected): void
    {
        $pattern = $this->autocompleter->createSearchSimplePatternPublic('abc', $searchType);

        $this->assertSame($expected, preg_match('/' . $pattern . '/i', $value) === 1);
    }

    public function testSearchSimplePatternEscapesUserInput(): void
    {
        $pattern = $this->autocompleter->createSearchSimplePatternPublic('a.b(c', 'equal');

        $this->assertSame('^a\.b\(c$', $pattern);
        $this->assertSame(1, preg_match('/' . $pattern . '/', 'a.b(c'));
        $this->assertSame(0, preg_match('/' . $pattern . '/', 'aXb(c'));
    }

    public function testSearchPatternEscapesUserInput(): void
    {
        $pattern = $this->autocompleter->createSearchPatternPublic('a.b', '^$search');

        $this->assertSame('^a\.b', $pattern);
    }

    public static function provideSearchSimplePatternCases(): Generator
    {
        yield 'any' => ['any', 'abc'];
        yield 'start' => ['start', '^abc'];
        yield 'end' => ['end', 'abc$'];
        yield 'equal' => ['equal', '^abc$'];
    }

    public static function provideSearchSimplePatternMatchesCases(): Generator
    {
        yield 'any contains' => ['any', 'xxABCxx', true];
        yield 'start match' => ['start', 'abcdef', true];
        yield 'start no match' => ['start', 'xabc', false];
        yield 'end match' => ['end', 'xxabc', true];
        yield 'end no match' => ['end', 'abcx', false];
        yield 'equal match' => ['equal', 'ABC', true];
        yield 'equal no match' => ['equal', 'abcd', false];
    }
}
