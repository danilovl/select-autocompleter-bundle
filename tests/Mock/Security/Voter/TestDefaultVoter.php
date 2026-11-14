<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Mock\Security\Voter;

use Danilovl\SelectAutocompleterBundle\Security\Voter\DefaultVoter;

class TestDefaultVoter extends DefaultVoter
{
    public function supportsPublic(string $attribute, mixed $subject): bool
    {
        return $this->supports($attribute, $subject);
    }
}
