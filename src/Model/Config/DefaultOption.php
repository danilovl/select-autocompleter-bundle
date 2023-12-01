<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

use Symfony\Component\Yaml\Yaml;

class DefaultOption
{
    private const string DIR_DEFAULT_CONFIG = '/../../Resources/config/default.yaml';

    public string $idProperty;
    public string $property;
    public string $propertySearchType;
    public ?string $image = null;
    public string $imageResultWidth;
    public string $imageSelectionWidth;
    public string $rootAlias;
    public int $limit;
    public string $baseTemplate;
    public string $widget;
    public string $rolePrefix;
    public SelectOption $selectOption;
    public Cdn $cdn;
    public Security $security;
    public Route $route;

    public static function fromDefaultYaml(): self
    {
        $yaml = Yaml::parseFile(__DIR__ . self::DIR_DEFAULT_CONFIG);

        return self::fromConfig($yaml['default']);
    }

    public static function fromConfig(array $parameters): self
    {
        $self = new self;
        $self->idProperty = $parameters['id_property'];
        $self->property = $parameters['property'];
        $self->propertySearchType = $parameters['property_search_type'];
        $self->image = $parameters['image'] ?? null;
        $self->imageResultWidth = $parameters['image_result_width'];
        $self->imageSelectionWidth = $parameters['image_selection_width'];
        $self->rootAlias = $parameters['root_alias'];
        $self->limit = $parameters['limit'];
        $self->baseTemplate = $parameters['base_template'];
        $self->widget = $parameters['widget'];
        $self->rolePrefix = $parameters['role_prefix'];
        $self->selectOption = SelectOption::fromConfig($parameters['select_option']);
        $self->cdn = Cdn::fromConfig($parameters['cdn']);
        $self->security = Security::fromConfig($parameters['security']);
        $self->route = Route::fromConfig($parameters['route']);

        return $self;
    }
}
