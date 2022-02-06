<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Service;

use Danilovl\SelectAutocompleterBundle\Model\Config\DependentSelect;
use Danilovl\SelectAutocompleterBundle\Model\Paginator\PaginatorBuilderObject;
use Doctrine\DBAL\Connection;
use Danilovl\SelectAutocompleterBundle\Constant\{
    SearchConstant,
    OrderByConstant
};
use Danilovl\SelectAutocompleterBundle\Model\Autocompleter\AutocompleterQuery;
use Danilovl\SelectAutocompleterBundle\Tool\Paginator\Interfaces\PaginatorInterface;
use Danilovl\SelectAutocompleterBundle\Tool\Paginator\Paginator;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\ChoiceList\{
    EntityLoaderInterface,
    ORMQueryBuilderLoader
};
use RuntimeException;

class OrmAutocompleter extends BaseAutocompleter
{
    protected function createQueryBuilder(): QueryBuilder
    {
        if ($this->queryBuilder !== null) {
            return $this->queryBuilder;
        }

        $builder = $this->getManager()
            ->getRepository($this->config->class)
            ->createQueryBuilder($this->config->rootAliase);

        $this->addingWhere($builder);
        $this->excludedEntityId($builder);

        return $builder;
    }

    protected function createAutocompleterQueryBuilder(AutocompleterQuery $query): QueryBuilder
    {
        $builder = $this->createQueryBuilderByRepository($query);
        if ($builder !== null) {
            return $builder;
        }

        $builder = $this->createQueryBuilder();
        $this->addingSearchCondition($builder, $query);
        $this->addingDependentSelectCondition($builder, $query);
        $this->addingOrderBy($builder);

        $builder->setFirstResult($this->getOffset($query))
            ->setMaxResults($this->config->limit);

        return $builder;
    }

    private function addingWhere(QueryBuilder $builder): void
    {
        foreach ($this->config->where as $where) {
            $builder->andWhere($where);
        }
    }

    private function addingSearchCondition(QueryBuilder $builder, AutocompleterQuery $query): void
    {
        if (empty($query->search)) {
            return;
        }

        $alias = $this->config->rootAliase;
        $or = $builder->expr()->orX();

        if (empty($this->config->searchPattern)) {
            foreach ($this->config->searchSimple as $field => $searchType) {
                $end = in_array($searchType, [SearchConstant::START, SearchConstant::ANY]) ? '%' : '';
                $start = in_array($searchType, [SearchConstant::END, SearchConstant::ANY]) ? '%' : '';

                $or->add("{$alias}.{$field} LIKE :autocompleter_{$field}");
                $builder->setParameter("autocompleter_{$field}", $start . $query->search . $end);
            }
        }

        foreach ($this->config->searchPattern as $field => $pattern) {
            $or->add("{$alias}.{$field} LIKE :autocompleter_{$field}");
            $builder->setParameter("autocompleter_{$field}", str_replace('$search', $query->search, $pattern));
        }

        $builder->andWhere($or);
    }

    private function addingOrderBy(QueryBuilder $builder): void
    {
        $alias = $this->config->rootAliase;
        if (empty($this->config->orderBy)) {
            $sort = sprintf('%s.%s', $alias, $this->config->idProperty);
            $builder->addOrderBy($sort, OrderByConstant::ASC);

            return;
        }

        foreach ($this->config->orderBy as $sort => $order) {
            $builder->addOrderBy("{$alias}.$sort", $order);
        }
    }

    private function excludedEntityId(QueryBuilder $builder): void
    {
        $excludedEntityId = $this->config->excludedEntityId;
        if (!empty($excludedEntityId)) {
            $field = sprintf('%s.%s', $this->config->rootAliase, $this->config->idProperty);
            $builder->andWhere($builder->expr()->notIn($field, ':autocompleter_excluded_id'))
                ->setParameter('autocompleter_excluded_id', [1], Connection::PARAM_INT_ARRAY);
        }
    }

    private function addingDependentSelectCondition(
        QueryBuilder $builder,
        AutocompleterQuery $query
    ): void {
        $dependentName = $query->dependentName;
        $dependentIds = $query->dependentId;
        $dependentSelects = $this->config->dependentSelects;

        if (!empty($dependentName) &&
            !empty($dependentIds) &&
            !empty($dependentSelects)
        ) {
            $dependentIds = is_array($dependentIds) ? $dependentIds : [$dependentIds];
            $dependentName = array_search($dependentName, array_column($dependentSelects, 'name'));
            if ($dependentName === false) {
                throw new RuntimeException('Dependent name not found');
            }

            /* @var DependentSelect $dependentSelect */
            $dependentSelect = $dependentSelects[$dependentName];
            $property = sprintf('%s.%s', $this->config->rootAliase, $dependentSelect->parentProperty);

            if (!empty($dependentSelect->manyToMany)) {
                $lastAlies = null;

                foreach ($dependentSelect->manyToMany as $alias => $join) {
                    $builder->join($join, $alias);
                    $lastAlies = $alias;
                }

                $property = sprintf('%s.%s', $lastAlies, $dependentSelect->parentProperty);
            }

            $builder->andWhere($builder->expr()->in($property, ':autocompleter_parent_id'))
                ->setParameter('autocompleter_parent_id', $dependentIds, Connection::PARAM_INT_ARRAY);
        }
    }

    protected function createPaginator(): PaginatorInterface
    {
        return new Paginator($this->getPaginatorBuilder());
    }

    private function getPaginatorBuilder(): PaginatorBuilderObject
    {
        $paginatorBuilder = new PaginatorBuilderObject;
        $paginatorBuilder->originQueryBuilder = $this->createQueryBuilder();
        $paginatorBuilder->autocompleterQueryBuilder = $this->createAutocompleterQueryBuilder($this->autocompleterQuery);

        return $paginatorBuilder;
    }

    protected function getLoader(): EntityLoaderInterface
    {
        return new ORMQueryBuilderLoader($this->createQueryBuilder());
    }
}
