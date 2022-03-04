<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Security\Voter;

use Danilovl\SelectAutocompleterBundle\Constant\{
    VoterSupportConstant,
    SecurityConditionConstant
};
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

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, self::SUPPORTS, true)) {
            return false;
        }

        if (!$subject instanceof AutocompleterInterface) {
            return false;
        }

        return $this->checkHasRole($subject);
    }

    /**
     * @param AutocompleterInterface $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if ($attribute !== VoterSupportConstant::GET_DATA) {
            throw new LogicException('This code should not be reached!');
        }

        $roles = $subject->getConfig()->security->role;
        if (count($roles) === 0) {
            return true;
        }

        $result = [];
        foreach ($roles as $role) {
            $result[] = $this->security->isGranted($role);
        }

        return $this->checkCondition($result, $subject->getConfig()->security->condition);
    }

    private function checkHasRole(AutocompleterInterface $subject): bool
    {
        return !empty($subject->getConfig()->security->role);
    }

    private function checkCondition(array $result, string $condition): bool
    {
        $positiveResult = array_filter($result);

        if ($condition === SecurityConditionConstant::OR && count($positiveResult) > 0) {
            return true;
        }

        if ($condition === SecurityConditionConstant::AND && count($positiveResult) === count($result)) {
            return true;
        }

        return false;
    }
}
