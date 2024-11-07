<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Autocompleter;

use Symfony\Component\HttpFoundation\Request;

readonly class AutocompleterQuery
{
    public function __construct(
        public string $search,
        public int $page,
        public ?string $dependentName = null,
        public array $dependentId = [],
        public array $extra = []
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            trim($request->query->getString('search')),
            $request->query->getInt('page', 1),
            $request->query->getString('dependentName'),
            $request->query->all('dependentId'),
            $request->query->all('extra')
        );
    }
}
