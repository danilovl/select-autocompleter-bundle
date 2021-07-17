<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tools\Paginator\Adapters;

use Danilovl\SelectAutocompleterBundle\Model\Paginator\PaginatorBuilderObject;
use Danilovl\SelectAutocompleterBundle\Tools\Paginator\Interfaces\PaginatorAdapterInterface;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrineORMPaginator;
use Doctrine\ORM\{
    Query,
    QueryBuilder
};

class OrmAdapter implements PaginatorAdapterInterface
{
    private DoctrineORMPaginator $ormPaginator;

    public function __construct(PaginatorBuilderObject $paginatorBuilderObject)
    {
        $this->ormPaginator = new DoctrineORMPaginator($paginatorBuilderObject->autocompleterQueryBuilder);
    }

    public function getTotalCount(): int
    {
        return $this->ormPaginator->count();
    }

    public function getResult(): array
    {
        return iterator_to_array($this->ormPaginator->getIterator());
    }
}
