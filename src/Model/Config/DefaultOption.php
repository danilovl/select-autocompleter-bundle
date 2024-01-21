<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

use Symfony\Component\Yaml\Yaml;

readonly class DefaultOption
{
    private const string DIR_DEFAULT_CONFIG = '/../../Resources/config/default.yaml';

    private function __construct(
        public string $idProperty,
        public string $property,
        public string $propertySearchType,
        public ?string $image,
        public string $imageResultWidth,
        public string $imageSelectionWidth,
        public string $rootAlias,
        public int $limit,
        public string $baseTemplate,
        public string $widget,
        public string $rolePrefix,
        public SelectOption $selectOption,
        public Cdn $cdn,
        public Security $security,
        public Route $route
    ) {}

    public static function fromDefaultYaml(): self
    {
        /** @var array{default: array} $yaml */
        $yaml = Yaml::parseFile(__DIR__ . self::DIR_DEFAULT_CONFIG);

        return self::fromConfig($yaml['default']);
    }

    public static function fromConfig(array $parameters): self
    {
        return new self(
            $parameters['id_property'],
            $parameters['property'],
            $parameters['property_search_type'],
            $parameters['image'] ?? null,
            $parameters['image_result_width'],
            $parameters['image_selection_width'],
            $parameters['root_alias'],
            $parameters['limit'],
            $parameters['base_template'],
            $parameters['widget'],
            $parameters['role_prefix'],
            SelectOption::fromConfig($parameters['select_option']),
            Cdn::fromConfig($parameters['cdn']),
            Security::fromConfig($parameters['security']),
            Route::fromConfig($parameters['route'])
        );
    }
}
