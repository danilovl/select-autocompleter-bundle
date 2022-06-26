<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Constant;

use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;

enum AuthenticatedVoterConstant
{
    final public const IS_AUTHENTICATED_FULLY = AuthenticatedVoter::IS_AUTHENTICATED_FULLY;
    final public const IS_AUTHENTICATED_REMEMBERED = AuthenticatedVoter::IS_AUTHENTICATED_REMEMBERED;
    final public const IS_AUTHENTICATED = AuthenticatedVoter::IS_AUTHENTICATED;
    final public const IS_IMPERSONATOR = AuthenticatedVoter::IS_IMPERSONATOR;
    final public const IS_REMEMBERED = AuthenticatedVoter::IS_REMEMBERED;
    final public const PUBLIC_ACCESS = AuthenticatedVoter::PUBLIC_ACCESS;

    final public const VOTERS = [
        self::IS_AUTHENTICATED_FULLY,
        self::IS_AUTHENTICATED_REMEMBERED,
        self::IS_AUTHENTICATED,
        self::IS_IMPERSONATOR,
        self::IS_REMEMBERED,
        self::PUBLIC_ACCESS,
    ];

    public static function supportsAttribute(string $attribute): bool
    {
        return in_array($attribute, self::VOTERS, true);
    }
}