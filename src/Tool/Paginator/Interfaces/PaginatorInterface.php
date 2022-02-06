<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tool\Paginator\Interfaces;

interface PaginatorInterface
{
    public function getTotalCount(): int;

    public function getResult(): array;
}
