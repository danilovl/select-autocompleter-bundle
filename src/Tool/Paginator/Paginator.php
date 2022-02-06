<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tool\Paginator;

use Danilovl\SelectAutocompleterBundle\Model\Paginator\PaginatorBuilderObject;
use Danilovl\SelectAutocompleterBundle\Tool\Paginator\Interfaces\{
    PaginatorInterface,
    PaginatorAdapterInterface
};

class Paginator implements PaginatorInterface
{
    private PaginatorAdapterInterface $adapter;

    public function __construct(PaginatorBuilderObject $paginatorBuilderObject)
    {
        $this->adapter = (new PaginatorAdapterStrategy($paginatorBuilderObject))->chooseAdapter();
    }

    public function getTotalCount(): int
    {
        return $this->adapter->getTotalCount();
    }

    public function getResult(): array
    {
        return $this->adapter->getResult();
    }
}
