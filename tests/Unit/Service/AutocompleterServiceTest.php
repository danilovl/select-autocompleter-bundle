<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Unit\Service;

use Danilovl\SelectAutocompleterBundle\Interfaces\{
    AutocompleterInterface,
    AutocompleterContainerInterface
};
use Danilovl\SelectAutocompleterBundle\Exception\NotImplementedGrantedException;
use Danilovl\SelectAutocompleterBundle\Model\Autocompleter\AutocompleterQuery;
use Danilovl\SelectAutocompleterBundle\Model\Config\Config;
use Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat\Result;
use Danilovl\SelectAutocompleterBundle\Service\AutocompleterService;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AutocompleterServiceTest extends TestCase
{
    #[DataProvider('provideAutocompleteGrantedCases')]
    public function testAutocompleteGranted(bool $publicAccess, int $grantedStatus): void
    {
        $expected = Result::fromConfig([]);

        $autocompleter = $this->createAutocompleterMock($publicAccess);
        $autocompleter->method('isGranted')->willReturn($grantedStatus);
        $autocompleter->expects($this->once())
            ->method('autocomplete')
            ->with($this->callback(static function (AutocompleterQuery $query): bool {
                return $query->search === 'text' && $query->page === 2;
            }))
            ->willReturn($expected);

        $request = new Request(['search' => ' text ', 'page' => '2']);
        $result = $this->createService($autocompleter)->autocompeteFromRequest($request, 'test');

        $this->assertSame($expected, $result);
    }

    #[DataProvider('provideAutocompleteDeniedCases')]
    public function testAutocompleteDenied(int $grantedStatus): void
    {
        $autocompleter = $this->createAutocompleterMock(false);
        $autocompleter->method('isGranted')->willReturn($grantedStatus);
        $autocompleter->expects($this->never())->method('autocomplete');

        $this->expectException(AccessDeniedException::class);

        $this->createService($autocompleter)->autocompeteFromRequest(new Request, 'test');
    }

    public function testDefaultIsGrantedWithoutTokenDenied(): void
    {
        $autocompleter = $this->createAutocompleterMock(false);
        $autocompleter->method('isGranted')->willThrowException(new NotImplementedGrantedException);
        $autocompleter->expects($this->never())->method('autocomplete');

        $this->expectException(AccessDeniedException::class);

        $this->createService($autocompleter)->autocompeteFromRequest(new Request, 'test');
    }

    public function testDefaultIsGrantedPublicAccess(): void
    {
        $expected = Result::fromConfig([]);

        $autocompleter = $this->createAutocompleterMock(true);
        $autocompleter->method('isGranted')->willThrowException(new NotImplementedGrantedException);
        $autocompleter->expects($this->once())->method('autocomplete')->willReturn($expected);

        $result = $this->createService($autocompleter)->autocompeteFromRequest(new Request, 'test');

        $this->assertSame($expected, $result);
    }

    public function testIsGrantedPublicAccessAbstain(): void
    {
        $autocompleter = $this->createAutocompleterStub(true);

        $status = $this->createService($autocompleter)->isGranted($autocompleter);

        $this->assertSame(VoterInterface::ACCESS_ABSTAIN, $status);
    }

    public function testIsGrantedWithoutTokenDenied(): void
    {
        $autocompleter = $this->createAutocompleterStub(false);

        $status = $this->createService($autocompleter)->isGranted($autocompleter);

        $this->assertSame(VoterInterface::ACCESS_DENIED, $status);
    }

    public function testGetByNameNotFound(): void
    {
        $autocompleterContainer = $this->createStub(AutocompleterContainerInterface::class);
        $autocompleterContainer->method('has')->willReturn(false);

        $service = new AutocompleterService(new Container, $autocompleterContainer, new TokenStorage);

        $this->expectException(NotFoundHttpException::class);

        $service->getByName('not-existing');
    }

    public static function provideAutocompleteGrantedCases(): Generator
    {
        yield 'private granted' => [false, VoterInterface::ACCESS_GRANTED];
        yield 'public abstain' => [true, VoterInterface::ACCESS_ABSTAIN];
        yield 'public granted' => [true, VoterInterface::ACCESS_GRANTED];
        yield 'public denied ignored' => [true, VoterInterface::ACCESS_DENIED];
    }

    public static function provideAutocompleteDeniedCases(): Generator
    {
        yield 'private denied' => [VoterInterface::ACCESS_DENIED];
        yield 'private abstain' => [VoterInterface::ACCESS_ABSTAIN];
    }

    private function createAutocompleterMock(bool $publicAccess): AutocompleterInterface&MockObject
    {
        $autocompleter = $this->createMock(AutocompleterInterface::class);
        $autocompleter->method('getConfig')->willReturn($this->createConfig($publicAccess));

        return $autocompleter;
    }

    private function createAutocompleterStub(bool $publicAccess): AutocompleterInterface
    {
        $autocompleter = $this->createStub(AutocompleterInterface::class);
        $autocompleter->method('getConfig')->willReturn($this->createConfig($publicAccess));

        return $autocompleter;
    }

    private function createService(AutocompleterInterface $autocompleter): AutocompleterService
    {
        $autocompleterContainer = $this->createStub(AutocompleterContainerInterface::class);
        $autocompleterContainer->method('has')->willReturn(true);
        $autocompleterContainer->method('get')->willReturn($autocompleter);

        return new AutocompleterService(new Container, $autocompleterContainer, new TokenStorage);
    }

    private function createConfig(bool $publicAccess): Config
    {
        return Config::fromConfig([
            'name' => 'test',
            'root_alias' => 'a',
            'id_property' => 'id',
            'property' => 'name',
            'property_search_type' => 'any',
            'image_result_width' => '100px',
            'image_selection_width' => '100px',
            'limit' => 10,
            'base_template' => 'base_template',
            'security' => [
                'public_access' => $publicAccess,
                'voter' => 'voter',
                'condition' => 'and',
                'role' => []
            ],
            'route' => [
                'name' => null,
                'parameters' => [],
                'extra' => []
            ]
        ]);
    }
}
