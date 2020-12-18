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