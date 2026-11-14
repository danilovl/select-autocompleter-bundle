<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Mock\Repository;

use Danilovl\SelectAutocompleterBundle\Model\Autocompleter\AutocompleterQuery;
use Danilovl\SelectAutocompleterBundle\Model\Config\Config;
use Danilovl\SelectAutocompleterBundle\Tests\Mock\Entity\Product;
use Doctrine\ORM\{
    QueryBuilder,
    EntityManagerInterface
};
use Doctrine\Persistence\ObjectRepository;

/**
 * @implements ObjectRepository<Product>
 */
class ProductRepository implements ObjectRepository
{
    public int $searchCallCount = 0;

    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    public function createQueryBuilder(string $alias): QueryBuilder
    {
        return new QueryBuilder($this->entityManager)
            ->select($alias)
            ->from(Product::class, $alias);
    }

    public function search(AutocompleterQuery $query, Config $config): QueryBuilder
    {
        $this->searchCallCount++;

        return $this->createQueryBuilder($config->rootAlias)
            ->andWhere("{$config->rootAlias}.name LIKE :custom_search")
            ->setParameter('custom_search', $query->search . '%')
            ->setMaxResults($config->limit);
    }

    public function find(mixed $id): ?object
    {
        return null;
    }

    public function findAll(): array
    {
        return [];
    }

    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return [];
    }

    public function findOneBy(array $criteria): ?object
    {
        return null;
    }

    public function getClassName(): string
    {
        return Product::class;
    }
}
