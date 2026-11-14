<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tool\Paginator\Adapters;

use Danilovl\SelectAutocompleterBundle\Model\Paginator\PaginatorBuilderObject;
use Danilovl\SelectAutocompleterBundle\Tool\Paginator\Interfaces\PaginatorAdapterInterface;

readonly class OdmAdapter implements PaginatorAdapterInterface
{
    public function __construct(private PaginatorBuilderObject $paginatorBuilderObject) {}

    public function getTotalCount(): int
    {
        /** @var int $count */
        $count = (clone $this->paginatorBuilderObject->autocompleterQueryBuilder)
            ->count()
            ->skip(0)
            ->limit(0)
            ->getQuery()
            ->execute();

        return $count;
    }

    public function getResult(): array
    {
        return $this->paginatorBuilderObject
            ->autocompleterQueryBuilder
            ->getQuery()
            ->toArray();
    }
}
