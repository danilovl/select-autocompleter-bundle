<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Service;

use Danilovl\SelectAutocompleterBundle\Model\Paginator\PaginatorBuilderObject;
use Doctrine\DBAL\ArrayParameterType;
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

class OrmAutocompleter extends BaseDoctrineAutocompleter
{
    protected function createQueryBuilder(): QueryBuilder
    {
        if ($this->queryBuilder !== null) {
            return $this->queryBuilder;
        }

        $builder = $this->getManager()
            ->getRepository($this->config->class)
            ->createQueryBuilder($this->config->rootAlias);

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

        $builder->setFirstResult($this->getOffset($query));
        $builder->setMaxResults($this->config->limit);

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

        $alias = $this->config->rootAlias;
        $or = $builder->expr()->orX();

        if (empty($this->config->searchPattern)) {
            foreach ($this->config->searchSimple as $field => $searchType) {
                if ($searchType === SearchConstant::EQUAL) {
                    $or->add("{$alias}.{$field} = :autocompleter_{$field}");
                    $builder->setParameter("autocompleter_{$field}", $query->search);
                } else {
                    $end = in_array($searchType, [SearchConstant::START, SearchConstant::ANY]) ? '%' : '';
                    $start = in_array($searchType, [SearchConstant::END, SearchConstant::ANY]) ? '%' : '';

                    $or->add("{$alias}.{$field} LIKE :autocompleter_{$field}");
                    $builder->setParameter("autocompleter_{$field}", $start . $query->search . $end);
                }
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
        $alias = $this->config->rootAlias;
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
        if (empty($excludedEntityId)) {
            return;
        }

        $field = sprintf('%s.%s', $this->config->rootAlias, $this->config->idProperty);

        $builder->andWhere($builder->expr()->notIn($field, ':autocompleter_excluded_id'));
        $builder->setParameter('autocompleter_excluded_id', [1], ArrayParameterType::INTEGER);
    }

    private function addingDependentSelectCondition(
        QueryBuilder $builder,
        AutocompleterQuery $query
    ): void {
        $dependentName = $query->dependentName;
        $dependentIds = $query->dependentId;
        $dependentSelectItems = $this->config->dependentSelects;

        if (!empty($dependentName) && !empty($dependentIds) && !empty($dependentSelectItems)) {
            $dependentSelect = null;
            foreach ($dependentSelectItems as $dependentSelectItem) {
                if ($dependentName === $dependentSelectItem->name) {
                    $dependentSelect = $dependentSelectItem;

                    break;
                }
            }

            if ($dependentSelect === null) {
                throw new RuntimeException('Dependent select not found');
            }

            $property = sprintf('%s.%s', $this->config->rootAlias, $dependentSelect->parentProperty);

            if (!empty($dependentSelect->manyToMany)) {
                $lastAlies = null;

                foreach ($dependentSelect->manyToMany as $alias => $join) {
                    $builder->join($join, $alias);
                    $lastAlies = $alias;
                }

                $property = sprintf('%s.%s', $lastAlies, $dependentSelect->parentProperty);
            }

            $builder->andWhere($builder->expr()->in($property, ':autocompleter_parent_id'));
            $builder->setParameter('autocompleter_parent_id', $dependentIds, ArrayParameterType::INTEGER);
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
