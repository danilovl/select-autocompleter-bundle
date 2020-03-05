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
    private PaginatorBuilderObject $paginatorBuilderObject;

    public function __construct(PaginatorBuilderObject $paginatorBuilderObject)
    {
        $this->paginatorBuilderObject = $paginatorBuilderObject;
    }

    public function chooseAdapter(): PaginatorAdapterInterface
    {
        $class = get_class($this->paginatorBuilderObject->autocompleterQueryBuilder);
        switch ($class) {
            case "Doctrine\ORM\QueryBuilder":
                $this->adapter = new OrmAdapter($this->paginatorBuilderObject);
                break;
            case "Doctrine\ODM\MongoDB\Query\Builder":
                $this->adapter = new OdmAdapter($this->paginatorBuilderObject);
                break;
            default:
                throw new RuntimeException(sprintf('Adapter for class %s not found', $class));
        }

        return $this->adapter;
    }

    public function getAdapter(): PaginatorAdapterInterface
    {
        return $this->adapter;
    }
}