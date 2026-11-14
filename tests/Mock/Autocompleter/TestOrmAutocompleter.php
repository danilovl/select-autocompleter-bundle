<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Mock\Autocompleter;

use Danilovl\SelectAutocompleterBundle\Model\Autocompleter\AutocompleterQuery;
use Danilovl\SelectAutocompleterBundle\Model\Config\Config;
use Danilovl\SelectAutocompleterBundle\Resolver\Config\AutocompleterConfigResolver;
use Danilovl\SelectAutocompleterBundle\Service\OrmAutocompleter;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class TestOrmAutocompleter extends OrmAutocompleter
{
    public function __construct(
        ManagerRegistry $registry,
        AutocompleterConfigResolver $resolver,
        Config $config
    ) {
        parent::__construct($registry, $resolver);

        $this->config = $config;
    }

    public function createQueryBuilderPublic(): QueryBuilder
    {
        return $this->createQueryBuilder();
    }

    public function createAutocompleterQueryBuilderPublic(AutocompleterQuery $query): QueryBuilder
    {
        return $this->createAutocompleterQueryBuilder($query);
    }

    /**
     * @param int[] $identifiers
     */
    public function createReverseTransformQueryBuilderPublic(array $identifiers): QueryBuilder
    {
        return $this->createReverseTransformQueryBuilder($identifiers);
    }

    /**
     * @return array<int, int|string>
     */
    public function normalizeDependentIdsPublic(mixed $ids): array
    {
        return $this->normalizeDependentIds($ids);
    }
}
