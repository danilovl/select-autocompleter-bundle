<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Unit\Security\Voter;

use PHPUnit\Framework\MockObject\MockObject;
use Danilovl\SelectAutocompleterBundle\Constant\{
    VoterSupportConstant,
    SecurityConditionConstant
};
use Danilovl\SelectAutocompleterBundle\Interfaces\AutocompleterInterface;
use Danilovl\SelectAutocompleterBundle\Model\Config\Config;
use Danilovl\SelectAutocompleterBundle\Security\Voter\DefaultVoter;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class DefaultVoterTest extends TestCase
{
    private array $defaultValidConfig = [
        'id_property' => 'id_property',
        'to_string' => [],
        'name' => 'null',
        'root_alias' => 'null',
        'property' => 'null',
        'property_search_type' => 'null',
        'image_result_width' => 'null',
        'image_selection_width' => 'null',
        'limit' => 0,
        'base_template' => 'null',
        'security' => [
            'public_access' => false,
            'voter' => 'voter',
            'condition' => SecurityConditionConstant::AND,
            'role' => []
        ],
        'route' => [
            'name' => null,
            'parameters' => [],
            'extra' => []
        ]
    ];

    private DefaultVoter $voter;

    private MockObject $security;

    protected function setUp(): void
    {
        $this->security = $this->createMock(Security::class);

        $this->voter = new class($this->security) extends DefaultVoter {
            public function supportsPublic(string $attribute, mixed $subject): bool
            {
                return $this->supports($attribute, $subject);
            }
        };
    }

    public function testNotSupportsAttribute(): void
    {
        $isSupport = $this->voter->supportsPublic('invalid', new stdClass);

        $this->assertFalse($isSupport);
    }

    public function testNotSupportsInstance(): void
    {
        $isSupport = $this->voter->supportsPublic(VoterSupportConstant::GET_DATA, new stdClass);

        $this->assertFalse($isSupport);
    }

    public function testSupports(): void
    {
        $config = Config::fromConfig($this->defaultValidConfig);

        $subjectMock = $this->createMock(AutocompleterInterface::class);
        $subjectMock->method('getConfig')->willReturn($config);

        $isSupport = $this->voter->supportsPublic(VoterSupportConstant::GET_DATA, $subjectMock);

        $this->assertTrue($isSupport);
    }

    public function testVoteEmptyRole(): void
    {
        $config = Config::fromConfig($this->defaultValidConfig);
        $subjectMock = $this->createMock(AutocompleterInterface::class);
        $subjectMock->method('getConfig')->willReturn($config);

        $token = $this->createMock(TokenInterface::class);

        $accessCode = $this->voter->vote($token, $subjectMock, [VoterSupportConstant::GET_DATA]);

        $this->assertSame(VoterInterface::ACCESS_GRANTED, $accessCode);
    }

    public function testVoteNotEmptyRoleGranted(): void
    {
        $defaultValidConfig = [...$this->defaultValidConfig, ...['security' => [
            'public_access' => false,
            'voter' => 'voter',
            'condition' => SecurityConditionConstant::AND,
            'role' => ['ROLE_USER']
        ]]];

        $config = Config::fromConfig($defaultValidConfig);
        $subjectMock = $this->createMock(AutocompleterInterface::class);
        $subjectMock->method('getConfig')->willReturn($config);

        $token = $this->createMock(TokenInterface::class);
        $this->security->method('isGranted')->willReturn(true);

        $accessCode = $this->voter->vote($token, $subjectMock, [VoterSupportConstant::GET_DATA]);

        $this->assertSame(VoterInterface::ACCESS_GRANTED, $accessCode);
    }

    public function testVoteNotEmptyRoleDenied(): void
    {
        $defaultValidConfig = [...$this->defaultValidConfig, ...['security' => [
            'public_access' => false,
            'voter' => 'voter',
            'condition' => SecurityConditionConstant::AND,
            'role' => ['ROLE_USER']
        ]]];

        $config = Config::fromConfig($defaultValidConfig);
        $subjectMock = $this->createMock(AutocompleterInterface::class);
        $subjectMock->method('getConfig')->willReturn($config);

        $token = $this->createMock(TokenInterface::class);
        $this->security->method('isGranted')->willReturn(false);

        $accessCode = $this->voter->vote($token, $subjectMock, [VoterSupportConstant::GET_DATA]);

        $this->assertSame(VoterInterface::ACCESS_DENIED, $accessCode);
    }
}
