<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Security\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\{
    Vote,
    Voter
};
use Danilovl\SelectAutocompleterBundle\Constant\{
    VoterSupportConstant,
    SecurityConditionConstant
};
use Danilovl\SelectAutocompleterBundle\Interfaces\AutocompleterInterface;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Bundle\SecurityBundle\Security;

class DefaultVoter extends Voter
{
    private const array SUPPORTS = [
        VoterSupportConstant::GET_DATA
    ];

    public function __construct(private readonly Security $security) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, self::SUPPORTS, true)) {
            return false;
        }

        if (!$subject instanceof AutocompleterInterface) {
            return false;
        }

        return $this->checkSupports($subject);
    }

    /**
     * @param AutocompleterInterface $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
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

    private function checkSupports(AutocompleterInterface $subject): bool
    {
        return !$subject->getConfig()->security->publicAccess;
    }

    private function checkCondition(array $result, string $condition): bool
    {
        $positiveResult = array_filter($result);

        if ($condition === SecurityConditionConstant::OR && count($positiveResult) > 0) {
            return true;
        }

        return $condition === SecurityConditionConstant::AND && count($positiveResult) === count($result);
    }
}
