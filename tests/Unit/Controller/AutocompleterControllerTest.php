<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Unit\Controller;

use Danilovl\SelectAutocompleterBundle\Controller\AutocompleterController;
use Danilovl\SelectAutocompleterBundle\Exception\NotImplementedMethodException;
use Danilovl\SelectAutocompleterBundle\Model\Autocompleter\AutocompleterQuery;
use Danilovl\SelectAutocompleterBundle\Model\Config\Config;
use Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat\{
    Result,
    Pagination
};
use Danilovl\SelectAutocompleterBundle\Resolver\Config\AutocompleterConfigResolver;
use Danilovl\SelectAutocompleterBundle\Service\{
    BaseAutocompleter,
    OrmAutocompleter,
    AutocompleterService,
    AutocompleterContainer
};
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use StdClass;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\{
    Request,
    JsonResponse
};
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class AutocompleterControllerTest extends TestCase
{
    private AutocompleterService $autocompleterService;

    protected function setUp(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $autocompleterConfigResolver = $this->createMock(AutocompleterConfigResolver::class);

        $ormAutocompleter = new class($managerRegistry, $autocompleterConfigResolver) extends OrmAutocompleter {
            public function __construct(ManagerRegistry $registry, AutocompleterConfigResolver $resolver)
            {
                parent::__construct($registry, $resolver);

                $this->config = Config::fromConfig([
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
                        'public_access' => true,
                        'voter' => 'voter',
                        'condition' => 'condition',
                        'role' => []
                    ],
                    'route' => [
                        'name' => null,
                        'parameters' => [],
                        'extra' => []
                    ]
                ]);
            }

            public function autocomplete(AutocompleterQuery $query): Result
            {
                $stdClass1 = new StdClass;
                $stdClass1->id = 1;
                $stdClass1->name = 'Product id 1';

                $stdClass2 = new StdClass;
                $stdClass2->id = 1_000;
                $stdClass2->name = 'Product id 1000';

                $results = [
                    1 => [$stdClass1, $stdClass2],
                    2 => []
                ];

                $pagination = Pagination::fromConfig([
                    'more' => $query->page === 1
                ]);

                return Result::fromConfig([
                    'results' => $this->transformObjectsToItem($results[$query->page]),
                    'pagination' => $pagination
                ]);
            }

            public function isGranted(): int
            {
                return VoterInterface::ACCESS_GRANTED;
            }
        };

        $withoutImplementationAutocompleter = new class($autocompleterConfigResolver) extends BaseAutocompleter {
            public function __construct(AutocompleterConfigResolver $resolver)
            {
                parent::__construct($resolver);

                $this->config = Config::fromConfig([
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
                        'public_access' => true,
                        'voter' => 'voter',
                        'condition' => 'condition',
                        'role' => []
                    ],
                    'route' => [
                        'name' => null,
                        'parameters' => [],
                        'extra' => []
                    ]
                ]);
            }
        };

        $container = new Container;
        $container->set('ormAutocompleterService', $ormAutocompleter);
        $container->set('withoutImplementationAutocompleterService', $withoutImplementationAutocompleter);

        $autocompleterContainer = new AutocompleterContainer($container);
        $autocompleterContainer->addAutocompleter('ormAutocompleter', 'ormAutocompleterService');
        $autocompleterContainer->addAutocompleter('withoutImplementationAutocompleter', 'withoutImplementationAutocompleterService');

        $tokenStorage = new TokenStorage;

        $this->autocompleterService = new AutocompleterService(
            $container,
            $autocompleterContainer,
            $tokenStorage
        );
    }

    public function testAutocompleteNotFoundHttpException(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $autocompleterController = new AutocompleterController($this->autocompleterService);

        $autocompleterController->autocomplete(new Request, 'not-existing');
    }

    public function testNotImplementedMethodException(): void
    {
        $this->expectException(NotImplementedMethodException::class);
        $this->expectExceptionMessage('Need implement logic for method "Danilovl\SelectAutocompleterBundle\Service\BaseAutocompleter::autocomplete');

        $autocompleterController = new AutocompleterController($this->autocompleterService);

        $autocompleterController->autocomplete(new Request, 'withoutImplementationAutocompleter');
    }

    public function testAutocomplete(): void
    {
        $autocompleterController = new AutocompleterController($this->autocompleterService);

        $request = new Request(query: ['search' => 'text', 'page' => 1]);
        $result = $autocompleterController->autocomplete($request, 'ormAutocompleter');

        $expected = new JsonResponse([
            'results' => [
                [
                    'id' => 1,
                    'text' => 'Product id 1',
                    'image' => null,
                    'children' => null
                ],
                [
                    'id' => 1_000,
                    'text' => 'Product id 1000',
                    'image' => null,
                    'children' => null
                ]
            ],
            'pagination' => ['more' => true]
        ]);

        $this->assertEquals($expected, $result);
    }

    public function testAutocompleteEmpty(): void
    {
        $autocompleterController = new AutocompleterController($this->autocompleterService);

        $request = new Request(query: ['search' => 'text', 'page' => 2]);
        $result = $autocompleterController->autocomplete($request, 'ormAutocompleter');

        $expected = new JsonResponse([
            'results' => [],
            'pagination' => ['more' => false]
        ]);

        $this->assertEquals($expected, $result);
    }
}
