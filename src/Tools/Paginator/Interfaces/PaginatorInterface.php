<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tools\Paginator\Interfaces;

interface PaginatorInterface
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
