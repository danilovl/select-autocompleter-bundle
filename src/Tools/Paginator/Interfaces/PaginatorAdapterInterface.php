<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tools\Paginator\Interfaces;

interface PaginatorAdapterInterface
{
    /**
     * @return int
     */
    public function getTotalCount(): int;

    /**
     * @return array
     */
    public function getResult(): array;
}
