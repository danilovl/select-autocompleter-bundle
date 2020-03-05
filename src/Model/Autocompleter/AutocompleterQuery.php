<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Autocompleter;

use Symfony\Component\HttpFoundation\Request;

class AutocompleterQuery
{
    public ?string $search = null;
    public ?int $page = null;
    public ?string $dependentName = null;
    public array $dependentId = [];
    public array $extra = [];

    public static function fromRequest(Request $request): self
    {
        $query = new self;
        $query->search = $request->get('search');
        $query->page = $request->get('page', 1);
        $query->dependentName = $request->get('dependentName');
        $query->dependentId = $request->get('dependentId') ?? [];
        $query->extra = $request->get('extra') ?? [];

        return $query;
    }
}