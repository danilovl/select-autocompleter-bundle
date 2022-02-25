<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Service;

use Danilovl\SelectAutocompleterBundle\Model\Autocompleter\AutocompleterQuery;
use Danilovl\SelectAutocompleterBundle\Model\Config\Config;
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
use Symfony\Component\OptionsResolver\Options;

abstract class BaseDoctrineAutocompleter extends BaseAutocompleter
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

        $result = new Result;
        $result->results = $this->transformObjectsToItem($paginator->getResult());
        $result->pagination = $pagination;

        return $result;
    }

    public function reverseTransform(array $identifiers): array
    {
        return $this->getLoader()
            ->getEntitiesByIds($this->config->idProperty, $identifiers);
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
