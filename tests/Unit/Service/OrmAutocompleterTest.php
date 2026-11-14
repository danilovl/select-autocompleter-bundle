<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Unit\Service;

use Danilovl\SelectAutocompleterBundle\Model\Autocompleter\AutocompleterQuery;
use Danilovl\SelectAutocompleterBundle\Model\Config\Config;
use Danilovl\SelectAutocompleterBundle\Resolver\Config\AutocompleterConfigResolver;
use Danilovl\SelectAutocompleterBundle\Tests\Mock\Autocompleter\TestOrmAutocompleter;
use Danilovl\SelectAutocompleterBundle\Tests\Mock\Entity\Product;
use Danilovl\SelectAutocompleterBundle\Tests\Mock\Repository\ProductRepository;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\ORM\{
    EntityManagerInterface
};
use Doctrine\ORM\Query\Expr;
use Doctrine\Persistence\{
    ObjectManager,
    ManagerRegistry
};
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class OrmAutocompleterTest extends TestCase
{
    private ProductRepository $repository;

    protected function setUp(): void
    {
        $entityManager = $this->createStub(EntityManagerInterface::class);
        $entityManager->method('getExpressionBuilder')->willReturn(new Expr);

        $this->repository = new ProductRepository($entityManager);
    }

    public function testCreateQueryBuilderReturnsCleanBuilderOnEveryCall(): void
    {
        $autocompleter = $this->createAutocompleter(['search_simple' => ['name' => 'any']]);

        $autocompleterBuilder = $autocompleter->createAutocompleterQueryBuilderPublic(new AutocompleterQuery('abc', 3));
        $first = $autocompleter->createQueryBuilderPublic();
        $second = $autocompleter->createQueryBuilderPublic();

        $this->assertNotSame($autocompleterBuilder, $first);
        $this->assertNotSame($first, $second);
        $this->assertSame('SELECT p FROM ' . Product::class . ' p', $first->getDQL());
        $this->assertNull($first->getMaxResults());
        $this->assertSame(0, $first->getFirstResult());
        $this->assertNull($first->getParameter('autocompleter_name'));
    }

    public function testCreateQueryBuilderIsNotAffectedByRepositoryMethod(): void
    {
        $autocompleter = $this->createAutocompleter(['repository' => ['method' => 'search']]);

        $repositoryBuilder = $autocompleter->createAutocompleterQueryBuilderPublic(new AutocompleterQuery('abc'));
        $builder = $autocompleter->createQueryBuilderPublic();

        $this->assertSame(1, $this->repository->searchCallCount);
        $this->assertSame(10, $repositoryBuilder->getMaxResults());
        $this->assertSame('abc%', $repositoryBuilder->getParameter('custom_search')?->getValue());

        $this->assertNotSame($repositoryBuilder, $builder);
        $this->assertSame('SELECT p FROM ' . Product::class . ' p', $builder->getDQL());
        $this->assertNull($builder->getMaxResults());
        $this->assertNull($builder->getParameter('custom_search'));
    }

    public function testRepositoryMethodIsCalledForEveryQuery(): void
    {
        $autocompleter = $this->createAutocompleter(['repository' => ['method' => 'search']]);

        $first = $autocompleter->createAutocompleterQueryBuilderPublic(new AutocompleterQuery('first'));
        $second = $autocompleter->createAutocompleterQueryBuilderPublic(new AutocompleterQuery('second'));

        $this->assertSame(2, $this->repository->searchCallCount);
        $this->assertNotSame($first, $second);
        $this->assertSame('first%', $first->getParameter('custom_search')?->getValue());
        $this->assertSame('second%', $second->getParameter('custom_search')?->getValue());
    }

    public function testExcludedEntityIdUsesConfiguredValues(): void
    {
        $autocompleter = $this->createAutocompleter(['excluded_entity_id' => [5, 7]]);

        $builder = $autocompleter->createQueryBuilderPublic();
        $parameter = $builder->getParameter('autocompleter_excluded_id');

        $this->assertStringContainsString('p.id NOT IN(:autocompleter_excluded_id)', $builder->getDQL());
        $this->assertSame([5, 7], $parameter?->getValue());
        $this->assertSame(ArrayParameterType::INTEGER, $parameter->getType());
    }

    public function testExcludedEntityIdIsSkippedWhenEmpty(): void
    {
        $builder = $this->createAutocompleter()->createQueryBuilderPublic();

        $this->assertStringNotContainsString('NOT IN', $builder->getDQL());
        $this->assertNull($builder->getParameter('autocompleter_excluded_id'));
    }

    public function testWhereConditionsAreApplied(): void
    {
        $builder = $this->createAutocompleter(['where' => ['p.active = true']])->createQueryBuilderPublic();

        $this->assertSame('SELECT p FROM ' . Product::class . ' p WHERE p.active = true', $builder->getDQL());
    }

    #[DataProvider('providePaginationCases')]
    public function testPagination(int $page, int $expectedOffset): void
    {
        $builder = $this->createAutocompleter()
            ->createAutocompleterQueryBuilderPublic(new AutocompleterQuery(page: $page));

        $this->assertSame($expectedOffset, $builder->getFirstResult());
        $this->assertSame(10, $builder->getMaxResults());
    }

    #[DataProvider('provideSearchSimpleCases')]
    public function testSearchSimple(string $searchType, string $expectedOperator, string $expectedValue): void
    {
        $builder = $this->createAutocompleter(['search_simple' => ['name' => $searchType]])
            ->createAutocompleterQueryBuilderPublic(new AutocompleterQuery('abc'));

        $this->assertStringContainsString("p.name {$expectedOperator} :autocompleter_name", $builder->getDQL());
        $this->assertSame($expectedValue, $builder->getParameter('autocompleter_name')?->getValue());
    }

    public function testSearchPatternHasPriorityOverSearchSimple(): void
    {
        $builder = $this->createAutocompleter([
            'search_simple' => ['name' => 'any'],
            'search_pattern' => ['code' => '$search%']
        ])->createAutocompleterQueryBuilderPublic(new AutocompleterQuery('abc'));

        $this->assertStringContainsString('p.code LIKE :autocompleter_code', $builder->getDQL());
        $this->assertSame('abc%', $builder->getParameter('autocompleter_code')?->getValue());
        $this->assertNull($builder->getParameter('autocompleter_name'));
    }

    public function testEmptySearchAddsNoCondition(): void
    {
        $builder = $this->createAutocompleter(['search_simple' => ['name' => 'any']])
            ->createAutocompleterQueryBuilderPublic(new AutocompleterQuery);

        $this->assertStringNotContainsString('WHERE', $builder->getDQL());
    }

    public function testDefaultOrderByIdProperty(): void
    {
        $builder = $this->createAutocompleter()->createAutocompleterQueryBuilderPublic(new AutocompleterQuery);

        $this->assertStringEndsWith('ORDER BY p.id ASC', $builder->getDQL());
    }

    public function testDependentSelectCondition(): void
    {
        $autocompleter = $this->createAutocompleter([
            'dependent_selects' => [
                ['name' => 'shop', 'parent_property' => 'shop', 'parent_field' => 'shop']
            ]
        ]);

        $query = new AutocompleterQuery(dependentId: [
            'shop' => ['1', '', '2', null],
            'unknown' => ['3']
        ]);

        $builder = $autocompleter->createAutocompleterQueryBuilderPublic($query);
        $parameter = $builder->getParameter('autocompleter_dependent_shop');

        $this->assertStringContainsString('p.shop IN(:autocompleter_dependent_shop)', $builder->getDQL());
        $this->assertSame(['1', '2'], $parameter?->getValue());
        $this->assertNull($builder->getParameter('autocompleter_dependent_unknown'));
    }

    public function testDependentSelectConditionSkippedWhenIdsEmpty(): void
    {
        $autocompleter = $this->createAutocompleter([
            'dependent_selects' => [
                ['name' => 'shop', 'parent_property' => 'shop', 'parent_field' => 'shop']
            ]
        ]);

        $builder = $autocompleter->createAutocompleterQueryBuilderPublic(
            new AutocompleterQuery(dependentId: ['shop' => ['', null]])
        );

        $this->assertStringNotContainsString(':autocompleter_dependent_shop', $builder->getDQL());
    }

    public function testDependentSelectManyToManyJoin(): void
    {
        $autocompleter = $this->createAutocompleter([
            'dependent_selects' => [
                [
                    'name' => 'tag',
                    'parent_property' => 'id',
                    'parent_field' => 'tag',
                    'many_to_many' => ['t' => 'p.tags']
                ]
            ]
        ]);

        $builder = $autocompleter->createAutocompleterQueryBuilderPublic(
            new AutocompleterQuery(dependentId: ['tag' => ['4']])
        );

        $this->assertStringContainsString('INNER JOIN p.tags t', $builder->getDQL());
        $this->assertStringContainsString('t.id IN(:autocompleter_dependent_tag)', $builder->getDQL());
    }

    #[DataProvider('provideNormalizeDependentIdsCases')]
    public function testNormalizeDependentIds(mixed $ids, array $expected): void
    {
        $this->assertSame($expected, $this->createAutocompleter()->normalizeDependentIdsPublic($ids));
    }

    public function testReverseTransformQueryBuilderIgnoresLimitAndOffset(): void
    {
        $autocompleter = $this->createAutocompleter(['limit' => 2]);
        $identifiers = [1, 2, 3, 4, 5];

        $builder = $autocompleter->createReverseTransformQueryBuilderPublic($identifiers);

        $this->assertStringContainsString('p.id IN (:autocompleter_ids)', $builder->getDQL());
        $this->assertSame($identifiers, $builder->getParameter('autocompleter_ids')?->getValue());
        $this->assertSame(0, $builder->getFirstResult());
        $this->assertNull($builder->getMaxResults());
    }

    public static function providePaginationCases(): Generator
    {
        yield 'first page' => [1, 0];
        yield 'second page' => [2, 10];
        yield 'fifth page' => [5, 40];
    }

    public static function provideSearchSimpleCases(): Generator
    {
        yield 'any' => ['any', 'LIKE', '%abc%'];
        yield 'start' => ['start', 'LIKE', 'abc%'];
        yield 'end' => ['end', 'LIKE', '%abc'];
        yield 'equal' => ['equal', '=', 'abc'];
    }

    public static function provideNormalizeDependentIdsCases(): Generator
    {
        yield 'scalar' => ['5', ['5']];
        yield 'int' => [5, [5]];
        yield 'empty values' => [['', null], []];
        yield 'reindexed' => [[3 => '1', 5 => '', 7 => '2'], ['1', '2']];
        yield 'nested arrays and floats removed' => [['1', ['2'], 1.5, true], ['1']];
    }

    private function createAutocompleter(array $config = []): TestOrmAutocompleter
    {
        $objectManager = $this->createStub(ObjectManager::class);
        $objectManager->method('getRepository')->willReturn($this->repository);

        $registry = $this->createStub(ManagerRegistry::class);
        $registry->method('getManagerForClass')->willReturn($objectManager);

        return new TestOrmAutocompleter(
            $registry,
            $this->createStub(AutocompleterConfigResolver::class),
            $this->createConfig($config)
        );
    }

    private function createConfig(array $config): Config
    {
        return Config::fromConfig([
            'name' => 'product',
            'class' => Product::class,
            'root_alias' => 'p',
            'id_property' => 'id',
            'property' => 'name',
            'property_search_type' => 'any',
            'image_result_width' => '100px',
            'image_selection_width' => '100px',
            'limit' => 10,
            'base_template' => 'base_template',
            'security' => [
                'public_access' => true,
                'voter' => null,
                'condition' => 'and',
                'role' => []
            ],
            'route' => [
                'name' => null,
                'parameters' => [],
                'extra' => []
            ],
            ...$config
        ]);
    }
}
