<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Controller;

use Danilovl\SelectAutocompleterBundle\Services\AutocompleterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{
    Request,
    JsonResponse
};
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AutocompleterController
{
    private AutocompleterService $autocompleterService;

    public function __construct(AutocompleterService $autocompleterService)
    {
        $this->autocompleterService = $autocompleterService;
    }

    public function autocomplete(Request $request, string $name): JsonResponse
    {
        $result = $this->autocompleterService
            ->autocompeteFromRequest($request, $name)
            ->toArray();

        return new JsonResponse($result);
    }
}