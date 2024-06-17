<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tool\Paginator\Adapters;

use Danilovl\SelectAutocompleterBundle\Model\Paginator\PaginatorBuilderObject;
use Danilovl\SelectAutocompleterBundle\Tool\Paginator\Interfaces\PaginatorAdapterInterface;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrineORMPaginator;

class OrmAdapter implements PaginatorAdapterInterface
{
    private DoctrineORMPaginator $ormPaginator;

    public function __construct(PaginatorBuilderObject $paginatorBuilderObject)
    {
        $query = $paginatorBuilderObject->autocompleterQueryBuilder->getQuery();

        foreach ($paginatorBuilderObject->hits as $name => $value) {
            $query->setHint($name, $value);
        }

        $this->ormPaginator = new DoctrineORMPaginator($query);
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
