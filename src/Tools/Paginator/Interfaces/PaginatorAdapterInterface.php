<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tools\Paginator\Interfaces;

interface PaginatorAdapterInterface
{
    public function getTotalCount(): int;

    public function getResult(): array;
}
