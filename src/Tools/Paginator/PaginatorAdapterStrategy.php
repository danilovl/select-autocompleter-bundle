<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tools\Paginator;

use Danilovl\SelectAutocompleterBundle\Model\Paginator\PaginatorBuilderObject;
use Danilovl\SelectAutocompleterBundle\Tools\Paginator\Adapters\{
    OdmAdapter,
    OrmAdapter
};
use Danilovl\SelectAutocompleterBundle\Tools\Paginator\Interfaces\PaginatorAdapterInterface;
use RuntimeException;

class PaginatorAdapterStrategy
{
    private ?PaginatorAdapterInterface $adapter = null;

    public function __construct(private PaginatorBuilderObject $paginatorBuilderObject)
    {
    }

    public function chooseAdapter(): PaginatorAdapterInterface
    {
        $class = get_class($this->paginatorBuilderObject->autocompleterQueryBuilder);
        $this->adapter = match ($class) {
            "Doctrine\ORM\QueryBuilder" => new OrmAdapter($this->paginatorBuilderObject),
            "Doctrine\ODM\MongoDB\Query\Builder" => new OdmAdapter($this->paginatorBuilderObject),
            default => throw new RuntimeException(sprintf('Adapter for class %s not found', $class)),
        };

        return $this->adapter;
    }

    public function getAdapter(): PaginatorAdapterInterface
    {
        return $this->adapter;
    }
}
