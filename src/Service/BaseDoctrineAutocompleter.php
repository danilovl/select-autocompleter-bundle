<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Service;

use Danilovl\SelectAutocompleterBundle\Model\Autocompleter\AutocompleterQuery;
use Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat\{
    Result,
    Pagination
};
use Danilovl\SelectAutocompleterBundle\Resolver\Config\AutocompleterConfigResolver;
use Danilovl\SelectAutocompleterBundle\Tool\Paginator\Interfaces\PaginatorInterface;
use Doctrine\ODM\MongoDB\Query\Builder;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\{
    ObjectManager,
    ManagerRegistry
};
use InvalidArgumentException;
use RuntimeException;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

abstract class BaseDoctrineAutocompleter extends BaseAutocompleter
{
    protected QueryBuilder|Builder|null $queryBuilder = null;

    public function __construct(
        protected readonly ManagerRegistry $registry,
        AutocompleterConfigResolver $resolver
    ) {
        parent::__construct($resolver);
    }

    public function autocomplete(AutocompleterQuery $query): Result
    {
        $this->autocompleterQuery = $query;
        $paginator = $this->createPaginator();

        $pagination = new Pagination;
        $pagination->more = $paginator->getTotalCount() > ($this->config->limit * $query->page);

        return Result::fromConfig([
            'results' => $this->transformObjectsToItem($paginator->getResult()),
            'pagination' => $pagination
        ]);
    }

    /**
     * @param string[] $identifiers
     */
    public function reverseTransform(array $identifiers): array
    {
        $identifiers = array_map('intval', $identifiers);

        return $this->getLoader()->getEntitiesByIds(
            $this->config->idProperty,
            $identifiers
        );
    }

    public function reverseTransformResultIds(array $objects): array
    {
        return array_map(function (object $object): mixed {
            return (PropertyAccess::createPropertyAccessor())->getValue($object, $this->getConfig()->idProperty);
        }, $objects);
    }

    protected function getManager(): ObjectManager
    {
        $manager = $this->config->manager;
        if ($manager !== null) {
            return $this->registry->getManager($manager);
        }

        /** @var string $class */
        $class = $this->config->class;

        $manager = $this->registry->getManagerForClass($class);
        if ($manager === null) {
            throw new RuntimeException(sprintf(
                'Class "%s" seems not to be a managed Doctrine entity.',
                $this->config->class
            ));
        }

        return $manager;
    }

    protected function createQueryBuilderByRepository(AutocompleterQuery $query): QueryBuilder|Builder|null
    {
        $repositoryMethod = $this->config->repository->method;
        if ($repositoryMethod !== null) {
            $repository = $this->getManager()->getRepository($this->config->class);

            if (!method_exists($repository, $repositoryMethod)) {
                throw new InvalidArgumentException(sprintf('Callback function "%s" in Repository "%s" does not exist.', $repositoryMethod, get_class($repository)));
            }

            /** @var callable $callback */
            $callback = [$repository, $repositoryMethod];
            $this->queryBuilder = call_user_func_array($callback, [$query, $this->config]);

            return $this->queryBuilder;
        }

        return null;
    }

    protected function getOffset(AutocompleterQuery $query): int
    {
        return ($query->page - 1) * $this->config->limit;
    }

    abstract protected function getLoader(): EntityLoaderInterface;

    abstract protected function createPaginator(): PaginatorInterface;
}
