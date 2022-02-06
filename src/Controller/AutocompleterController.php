<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Controller;

use Danilovl\SelectAutocompleterBundle\Service\AutocompleterService;
use Symfony\Component\HttpFoundation\{
    Request,
    JsonResponse
};

class AutocompleterController
{
    public function __construct(private AutocompleterService $autocompleterService)
    {
    }

    public function autocomplete(Request $request, string $name): JsonResponse
    {
        $result = $this->autocompleterService
            ->autocompeteFromRequest($request, $name)
            ->toArray();

        return new JsonResponse($result);
    }
}
