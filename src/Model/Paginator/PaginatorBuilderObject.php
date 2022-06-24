<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Paginator;

use Doctrine\ODM\MongoDB\Query\Builder;
use Doctrine\ORM\QueryBuilder;

class PaginatorBuilderObject
{
    /**
     * @var QueryBuilder|Builder
     */
    public object $originQueryBuilder;

    /**
     * @var QueryBuilder|Builder
     */
    public object $autocompleterQueryBuilder;
}
