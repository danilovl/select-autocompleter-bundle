<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Services;

use Danilovl\SelectAutocompleterBundle\Model\Autocompleter\AutocompleterQuery;
use Danilovl\SelectAutocompleterBundle\Model\Config\Config;
use Danilovl\SelectAutocompleterBundle\Resolver\Config\AutocompleterConfigResolver;
use Danilovl\SelectAutocompleterBundle\Tools\Paginator\Interfaces\PaginatorInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ODM\MongoDB\Query\Builder;
use InvalidArgumentException;
use Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat\{
    Item,
    Result,
    Pagination
};
use Danilovl\SelectAutocompleterBundle\Services\Interfaces\AutocompleterInterface;
use Doctrine\Persistence\ObjectManager;
use RuntimeException;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\OptionsResolver\{
    Options,
    OptionsResolver
};

abstract class BaseAutocompleter implements AutocompleterInterface
{
    protected ?Config $config = null;
    protected ?Options $options = null;
    protected ?AutocompleterQuery $autocompleterQuery = null;

    /**
     * @var QueryBuilder|Builder|null
     */
    protected $queryBuilder;

    public function __construct(
        protected ManagerRegistry $registry,
        protected AutocompleterConfigResolver $resolver
    ) {
    }

    public function addConfig(array $options): void
    {
        $this->config = $this->resolver->resolveConfig($options);
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function reverseTransform(array $ids): array
    {
        return $this->getLoader()
            ->getEntitiesByIds($this->config->idProperty, $ids);
    }

    public function transformObjectsToItem(array $objects): array
    {
        return array_map(fn(object $object): Item => $this->transformObjectToItem($object), $objects);
    }

    public function transformObjectToItem(object $object): Item
    {
        return Item::formObject($object, $this->config);
    }

    public function autocomplete(AutocompleterQuery $query): Result
    {
        $this->autocompleterQuery = $query;
        $paginator = $this->createPaginator();

        $pagination = new Pagination;
        $pagination->more = $paginator->getTotalCount() > ($this->config->limit * $query->page);

        $result = new Result;
        $result->items = $this->transformObjectsToItem($paginator->getResult());
        $result->pagination = $pagination;

        return $result;
    }

    protected function getManager(): ObjectManager
    {
        $manager = $this->config->manager;
        if ($manager !== null) {
            return $this->registry->getManager($manager);
        }

        $manager = $this->registry->getManagerForClass($this->config->class);
        if ($manager === null) {
            throw new RuntimeException(sprintf(
                'Class "%s" seems not to be a managed Doctrine entity.',
                $this->config->class
            ));
        }

        return $manager;
    }

    /**
     * @return QueryBuilder|Builder|null
     */
    protected function createQueryBuilderByRepository(AutocompleterQuery $query)
    {
        $repositoryMethod = $this->config->repository->method;
        if ($repositoryMethod !== null) {
            $repository = $this->getManager()->getRepository($this->config->class);

            if (!method_exists($repository, $repositoryMethod)) {
                throw new InvalidArgumentException(sprintf('Callback function "%s" in Repository "%s" does not exist.', $repositoryMethod, get_class($repository)));
            }

            $this->queryBuilder = call_user_func_array([$repository, $repositoryMethod], [$query, $this->config]);

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