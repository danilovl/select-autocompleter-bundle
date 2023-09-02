<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Service;

use Danilovl\SelectAutocompleterBundle\Model\Paginator\PaginatorBuilderObject;
use Danilovl\SelectAutocompleterBundle\Constant\{
    SearchConstant,
    OrderByConstant
};
use Danilovl\SelectAutocompleterBundle\Model\Autocompleter\AutocompleterQuery;
use Danilovl\SelectAutocompleterBundle\Tool\Paginator\Interfaces\PaginatorInterface;
use Danilovl\SelectAutocompleterBundle\Tool\Paginator\Paginator;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Doctrine\Bundle\MongoDBBundle\Form\ChoiceList\MongoDBQueryBuilderLoader;
use Doctrine\ODM\MongoDB\Query\Builder;
use MongoDB\BSON\Regex;

class OdmAutocompleter extends BaseDoctrineAutocompleter
{
    protected function createBuilder(): Builder
    {
        if ($this->queryBuilder !== null) {
            return $this->queryBuilder;
        }

        $builder = $this->getManager()
            ->getRepository($this->config->class)
            ->createQueryBuilder();

        $this->addingWhere($builder);
        $this->excludedEntityId($builder);

        return $builder;
    }

    protected function createAutocompleterBuilder(AutocompleterQuery $query): Builder
    {
        $builder = $this->createQueryBuilderByRepository($query);
        if ($builder !== null) {
            return $builder;
        }

        $builder = $this->createBuilder();
        $this->addingSearchCondition($builder, $query);
        $this->addingSort($builder);

        $builder->skip($this->getOffset($query));
        $builder->limit($this->config->limit);

        return $builder;
    }

    private function addingSearchCondition(Builder $builder, AutocompleterQuery $query): void
    {
        if (empty($query->search)) {
            return;
        }

        $or = $builder->expr()->orX();

        if (empty($this->config->searchPattern)) {
            foreach ($this->config->searchSimple as $field => $searchType) {
                $end = in_array($searchType, [SearchConstant::START, SearchConstant::ANY]) ? '^' : '';
                $start = in_array($searchType, [SearchConstant::END, SearchConstant::ANY]) ? '&' : '';

                $pattern = sprintf('%s%s%s', $start, preg_quote($query->search), $end);
                $regex = new Regex($pattern, 'i');

                $or->addOr($builder)
                    ->expr()
                    ->field($field)
                    ->equals($regex);
            }
        }

        foreach ($this->config->searchPattern as $field => $pattern) {
            $searchTerm = str_replace('$search', $query->search, $pattern);
            $regex = new Regex($searchTerm, 'i');
            $or->addOr($builder)
                ->expr()
                ->field($field)
                ->equals($regex);
        }

        $builder->addAnd($or);
    }

    private function addingSort(Builder $builder): void
    {
        if (empty($this->config->orderBy)) {
            $builder->sort($this->config->property, OrderByConstant::ASC);

            return;
        }

        foreach ($this->config->orderBy as $sort => $order) {
            $builder->sort($sort, $order);
        }
    }

    private function addingWhere(Builder $builder): void
    {
        foreach ($this->config->where as $where) {
            $builder->addAnd($where);
        }
    }

    private function excludedEntityId(Builder $builder): void
    {
        $excludedEntityId = $this->config->excludedEntityId;
        if (!empty($excludedEntityId)) {
            $builder->field($this->config->idProperty)->notIn($excludedEntityId);
        }
    }

    protected function createPaginator(): PaginatorInterface
    {
        return new Paginator($this->getPaginatorBuilder());
    }

    private function getPaginatorBuilder(): PaginatorBuilderObject
    {
        $paginatorBuilder = new PaginatorBuilderObject;
        $paginatorBuilder->originQueryBuilder = $this->createBuilder();
        $paginatorBuilder->autocompleterQueryBuilder = $this->createAutocompleterBuilder($this->autocompleterQuery);

        return $paginatorBuilder;
    }

    protected function getLoader(): EntityLoaderInterface
    {
        return new MongoDBQueryBuilderLoader($this->createBuilder());
    }
}
