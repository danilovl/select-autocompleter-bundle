<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tools\Paginator\Adapters;

use Danilovl\SelectAutocompleterBundle\Model\Paginator\PaginatorBuilderObject;
use Danilovl\SelectAutocompleterBundle\Tools\Paginator\Interfaces\PaginatorAdapterInterface;
use Doctrine\ODM\MongoDB\Query\Builder;

class OdmAdapter implements PaginatorAdapterInterface
{
    public function __construct(private PaginatorBuilderObject $paginatorBuilderObject)
    {
    }

    public function getTotalCount(): int
    {
        return $this->paginatorBuilderObject
            ->originQueryBuilder
            ->getQuery()
            ->execute()
            ->count();
    }

    public function getResult(): array
    {
        return $this->paginatorBuilderObject
            ->autocompleterQueryBuilder
            ->getQuery()
            ->execute();
    }
}