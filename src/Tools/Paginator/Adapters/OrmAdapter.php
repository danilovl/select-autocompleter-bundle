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
    /**
     * @var DoctrineORMPaginator
     */
    private $ormPaginator;

    /**
     * @param PaginatorBuilderObject $paginatorBuilderObject
     */
    public function __construct(PaginatorBuilderObject $paginatorBuilderObject)
    {
        $this->ormPaginator = new DoctrineORMPaginator($paginatorBuilderObject->autocompleterQueryBuilder);
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->ormPaginator->count();
    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        return iterator_to_array($this->ormPaginator->getIterator());
    }
}