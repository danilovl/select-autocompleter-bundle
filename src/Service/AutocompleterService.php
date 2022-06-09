<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Service;

use Danilovl\SelectAutocompleterBundle\Constant\VoterSupportConstant;
use Danilovl\SelectAutocompleterBundle\Interfaces\{
    AutocompleterInterface,
    AutocompleterContainerInterface
};
use Danilovl\SelectAutocompleterBundle\Model\Autocompleter\AutocompleterQuery;
use Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat\Result;
use Exception;
use LogicException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AutocompleterService
{
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly AutocompleterContainerInterface $autocompleterContainer,
        private readonly TokenStorageInterface $tokenStorage
    ) {
    }

    public function autocompeteFromRequest(Request $request, string $name): Result
    {
        $autocompleter = $this->getByName($name);

        $this->denyAccessUnlessGranted($autocompleter);

        return $autocompleter->autocomplete(AutocompleterQuery::fromRequest($request));
    }

    public function getByName(string $name): AutocompleterInterface
    {
        if (!$this->autocompleterContainer->has($name)) {
            throw new NotFoundHttpException;
        }

        return $this->autocompleterContainer->get($name);
    }

    public function isGranted(AutocompleterInterface $autocompleter): int
    {
        $voterName = $autocompleter->getConfig()->security->voter;
        $publicAccess = $autocompleter->getConfig()->security->publicAccess;

        if ($publicAccess) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        $token = $this->tokenStorage->getToken();
        if (!$publicAccess && $token === null) {
            return VoterInterface::ACCESS_DENIED;
        }

        /** @var VoterInterface $voter */
        $voter = $this->container->get($voterName);

        return $voter->vote(
            $this->tokenStorage->getToken(),
            $autocompleter,
            [VoterSupportConstant::GET_DATA]
        );
    }

    private function denyAccessUnlessGranted(AutocompleterInterface $autocompleter): void
    {
        if ($this->isGranted($autocompleter) === VoterInterface::ACCESS_DENIED) {
            throw $this->createAccessDeniedException();
        }
    }

    private function createAccessDeniedException(
        string $message = 'Access Denied.',
        Exception $previous = null
    ): AccessDeniedException {
        if (!class_exists(AccessDeniedException::class)) {
            throw new LogicException('You can not use the "createAccessDeniedException" method if the Security component is not available. Try running "composer require symfony/security-bundle".');
        }

        return new AccessDeniedException($message, $previous);
    }
}
