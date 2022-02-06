<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Security\Voter;

use Danilovl\SelectAutocompleterBundle\Constant\VoterSupportConstant;
use Danilovl\SelectAutocompleterBundle\Interfaces\AutocompleterInterface;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class DefaultVoter extends Voter
{
    private const SUPPORTS = [
        VoterSupportConstant::GET_DATA
    ];

    public function __construct(private Security $security)
    {
    }

    protected function supports($attribute, $subject): bool
    {
        if (!in_array($attribute, self::SUPPORTS, true)) {
            return false;
        }

        if (!$subject instanceof AutocompleterInterface) {
            return false;
        }

        return $this->checkHasRole($subject);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if ($attribute === VoterSupportConstant::GET_DATA) {
            return $this->security->isGranted($subject->getConfig()->security->role);
        }

        throw new LogicException('This code should not be reached!');
    }

    private function checkHasRole(AutocompleterInterface $subject): bool
    {
        return !empty($subject->getConfig()->security->role);
    }
}
