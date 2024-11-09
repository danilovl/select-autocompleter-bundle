<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Unit\Model\Autocompleter;

use Danilovl\SelectAutocompleterBundle\Model\Autocompleter\AutocompleterQuery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class AutocompleterQueryTest extends TestCase
{
    public function testDefaultParameters(): void
    {
        $autocompleterQuery = new AutocompleterQuery;

        $this->assertSame('', $autocompleterQuery->search);
        $this->assertSame(1, $autocompleterQuery->page);
        $this->assertNull($autocompleterQuery->dependentName);
        $this->assertSame([], $autocompleterQuery->dependentId);
        $this->assertSame([], $autocompleterQuery->extra);
    }

    public function testFromRequestWithAllParameters(): void
    {
        $request = new Request([
            'search' => 'test search',
            'page' => '3',
            'dependentName' => 'exampleDependent',
            'dependentId' => ['1', '2', '3'],
            'extra' => ['key1' => 'value1', 'key2' => 'value2']
        ]);

        $autocompleterQuery = AutocompleterQuery::fromRequest($request);

        $this->assertSame('test search', $autocompleterQuery->search);
        $this->assertSame(3, $autocompleterQuery->page);
        $this->assertSame('exampleDependent', $autocompleterQuery->dependentName);
        $this->assertSame(['1', '2', '3'], $autocompleterQuery->dependentId);
        $this->assertSame(['key1' => 'value1', 'key2' => 'value2'], $autocompleterQuery->extra);
    }

    public function testFromRequestWithDefaultValues(): void
    {
        $autocompleterQuery = AutocompleterQuery::fromRequest(new Request);

        $this->assertSame('', $autocompleterQuery->search);
        $this->assertSame(1, $autocompleterQuery->page);
        $this->assertSame('', $autocompleterQuery->dependentName);
        $this->assertSame([], $autocompleterQuery->dependentId);
        $this->assertSame([], $autocompleterQuery->extra);
    }

    public function testFromRequestWithPartialParameters(): void
    {
        $request = new Request([
            'search' => 'partial search',
            'dependentName' => 'dependentPartial'
        ]);

        $autocompleterQuery = AutocompleterQuery::fromRequest($request);

        $this->assertSame('partial search', $autocompleterQuery->search);
        $this->assertSame(1, $autocompleterQuery->page);
        $this->assertSame('dependentPartial', $autocompleterQuery->dependentName);
        $this->assertSame([], $autocompleterQuery->dependentId);
        $this->assertSame([], $autocompleterQuery->extra);
    }

    public function testFromRequestTrimsSearchString(): void
    {
        $request = new Request([
            'search' => '   spaced search   '
        ]);

        $autocompleterQuery = AutocompleterQuery::fromRequest($request);

        $this->assertSame('spaced search', $autocompleterQuery->search);
    }
}
