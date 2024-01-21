<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

readonly class Config
{
    /**
     * @param DependentSelects[] $dependentSelects
     */
    private function __construct(
        public string $name,
        public ?string $class,
        public string $rootAlias,
        public bool $multiple,
        public string $idProperty,
        public string $property,
        public string $propertySearchType,
        public ?string $image,
        public string $imageResultWidth,
        public string $imageSelectionWidth,
        public array $excludedEntityId,
        public array $searchSimple,
        public array $searchPattern,
        public array $orderBy,
        public array $where,
        public ?string $manager,
        public int $limit,
        public ?string $widget,
        public string $baseTemplate,
        public ToString $toString,
        public Cdn $cdn,
        public SelectOption $selectOption,
        public Security $security,
        public Repository $repository,
        public Route $route,
        public array $dependentSelects
    ) {}

    public static function fromConfig(array $parameters): self
    {
        return new self(
            $parameters['name'] ?? null,
            $parameters['class'] ?? null,
            $parameters['root_alias'] ?? null,
            $parameters['multiple'] ?? false,
            $parameters['id_property'],
            $parameters['property'],
            $parameters['property_search_type'],
            $parameters['image'] ?? null,
            $parameters['image_result_width'],
            $parameters['image_selection_width'],
            $parameters['excluded_entity_id'] ?? [],
            $parameters['search_simple'] ?? [],
            $parameters['search_pattern'] ?? [],
            $parameters['order_by'] ?? [],
            $parameters['where'] ?? [],
            $parameters['manager'] ?? null,
            $parameters['limit'],
            $parameters['widget'] ?? null,
            $parameters['base_template'],
            ToString::fromConfig($parameters['to_string'] ?? []),
            Cdn::fromConfig($parameters['cdn'] ?? []),
            SelectOption::fromConfig($parameters['select_option'] ?? []),
            Security::fromConfig($parameters['security'] ?? []),
            Repository::fromConfig($parameters['repository'] ?? []),
            Route::fromConfig($parameters['route'] ?? []),
            DependentSelects::fromArrayConfig($parameters['dependent_selects'] ?? [])
        );
    }
}
