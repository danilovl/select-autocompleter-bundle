<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

use Symfony\Component\HttpFoundation\Request;

class Config
{
    public ?string $name = null;
    public ?string $class = null;
    public ?string $rootAliase = null;
    public bool $multiple = false;
    public ?string $idProperty = null;
    public ?string $property = null;
    public ?string $image = null;
    public ?string $imageResultWidth = null;
    public ?string $imageSelectionWidth = null;
    public array $excludedEntityId = [];
    public array $searchSimple = [];
    public array $searchPattern = [];
    public array $orderBy = [];
    public array $where = [];
    public ?string $manager = null;
    public ?int $limit = null;
    public ?string $widget = null;
    public ?string $baseTemplate = null;
    public ?ToString $toString = null;
    public ?Cdn $cdn = null;
    public ?SelectOption $selectOption = null;
    public ?Security $security = null;
    public ?Repository $repository = null;
    public ?array $dependentSelects = [];

    public static function fromConfig(array $parameters): self
    {
        $self = new self;
        $self->name = $parameters['name'] ?? null;
        $self->class = $parameters['class'] ?? null;
        $self->rootAliase = $parameters['root_alias'] ?? null;
        $self->multiple = $parameters['multiple'] ?? false;
        $self->idProperty = $parameters['id_property'] ?? null;
        $self->manager = $parameters['manager'] ?? null;
        $self->property = $parameters['property'] ?? null;
        $self->image = $parameters['image'] ?? null;
        $self->imageResultWidth = $parameters['image_result_width'] ?? null;
        $self->imageSelectionWidth = $parameters['image_selection_width'] ?? null;
        $self->excludedEntityId = $parameters['excluded_entity_id'] ?? [];
        $self->searchSimple = $parameters['search_simple'] ?? [];
        $self->searchPattern = $parameters['search_pattern'] ?? [];
        $self->orderBy = $parameters['order_by'] ?? [];
        $self->where = $parameters['where'] ?? [];
        $self->limit = $parameters['limit'] ?? null;
        $self->widget = $parameters['widget'] ?? null;
        $self->baseTemplate = $parameters['base_template'] ?? null;
        $self->toString = ToString::fromConfig($parameters['to_string'] ?? []);
        $self->selectOption = SelectOption::fromConfig($parameters['select_option'] ?? []);
        $self->cdn = Cdn::fromConfig($parameters['cdn'] ?? []);
        $self->security = Security::fromConfig($parameters['security'] ?? []);
        $self->repository = Repository::fromConfig($parameters['repository'] ?? []);
        $self->dependentSelects = DependentSelects::fromArrayConfig($parameters['dependent_selects'] ?? []);

        return $self;
    }
}
