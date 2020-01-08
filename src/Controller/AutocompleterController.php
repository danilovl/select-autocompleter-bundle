<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{
    Request,
    JsonResponse
};
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AutocompleterController extends AbstractController
{
    /**
     * @param Request $request
     * @param string $name
     * @return JsonResponse
     */
    public function autocomplete(Request $request, string $name): JsonResponse
    {
        $result = $this->get('danilovl.select_autocompleter.autocompleter')
            ->autocompeteFromRequest($request, $name)
            ->toArray();

        return new JsonResponse($result);
    }
}
