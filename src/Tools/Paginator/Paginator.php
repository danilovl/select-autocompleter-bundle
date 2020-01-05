<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tools\Paginator;

use Danilovl\SelectAutocompleterBundle\Model\Paginator\PaginatorBuilderObject;
use Danilovl\SelectAutocompleterBundle\Tools\Paginator\Interfaces\{
    PaginatorInterface,
    PaginatorAdapterInterface
};

class Paginator implements PaginatorInterface
{
    /**
     * @var PaginatorAdapterInterface
     */
    private $adapter;

    /**
     * @param PaginatorBuilderObject $paginatorBuilderObject
     */
    public function __construct(PaginatorBuilderObject $paginatorBuilderObject)
    {
        $this->adapter = (new PaginatorAdapterStrategy($paginatorBuilderObject))->chooseAdapter();
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->adapter->getTotalCount();
    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        return $this->adapter->getResult();
    }
}