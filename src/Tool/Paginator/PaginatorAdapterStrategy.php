<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tool\Paginator;

use Danilovl\SelectAutocompleterBundle\Model\Paginator\PaginatorBuilderObject;
use Danilovl\SelectAutocompleterBundle\Tool\Paginator\Adapters\{
    OdmAdapter,
    OrmAdapter
};
use Danilovl\SelectAutocompleterBundle\Tool\Paginator\Interfaces\PaginatorAdapterInterface;
use RuntimeException;

class PaginatorAdapterStrategy
{
    private ?PaginatorAdapterInterface $adapter = null;

    public function __construct(private readonly PaginatorBuilderObject $paginatorBuilderObject) {}

    public function chooseAdapter(): PaginatorAdapterInterface
    {
        $class = get_class($this->paginatorBuilderObject->autocompleterQueryBuilder);
        $this->adapter = match ($class) {
            'Doctrine\ORM\QueryBuilder' => new OrmAdapter($this->paginatorBuilderObject),
            'Doctrine\ODM\MongoDB\Query\Builder' => new OdmAdapter($this->paginatorBuilderObject),
            default => throw new RuntimeException(sprintf('Adapter for class %s not found', $class)),
        };

        return $this->adapter;
    }
}
