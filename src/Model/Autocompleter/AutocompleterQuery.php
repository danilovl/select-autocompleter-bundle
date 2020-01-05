<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Autocompleter;

use Symfony\Component\HttpFoundation\Request;

class AutocompleterQuery
{
    /**
     * @var string|null
     */
    public $search;

    /**
     * @var int|null
     */
    public $page;

    /**
     * @var array
     */
    public $dependentName = [];

    /**
     * @var array
     */
    public $dependentId = [];
    
    /**
     * @var array
     */
    public $extra = [];

    /**
     * @param Request $request
     * @return self
     */
    public static function fromRequest(Request $request): self
    {
        $query = new self;
        $query->search = $request->get('search');
        $query->page = $request->get('page', 1);
        $query->dependentName = $request->get('dependentName');
        $query->dependentId = $request->get('dependentId');
        $query->extra = $request->get('extra');

        return $query;
    }
}
