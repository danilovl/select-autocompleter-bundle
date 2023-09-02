<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Autocompleter;

use Symfony\Component\HttpFoundation\Request;

readonly class AutocompleterQuery
{
    public string $search;
    public int $page;
    public string $dependentName;
    public array $dependentId;
    public array $extra;

    public static function fromRequest(Request $request): self
    {
        $query = new self;
        $query->search = trim($request->query->getString('search'));
        $query->page = $request->query->getInt('page', 1);
        $query->dependentName = $request->query->getString('dependentName');
        $query->dependentId = $request->query->all('dependentId') ?? [];
        $query->extra = $request->query->all('extra') ?? [];

        return $query;
    }
}
