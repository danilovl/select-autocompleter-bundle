<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

use Symfony\Component\HttpFoundation\Request;

class Config
{
    /**
     * @var string|null
     */
    public $name;

    /**
     * @var string|null
     */
    public $class;

    /**
     * @var string|null
     */
    public $rootAliase;

    /**
     * @var bool
     */
    public $multiple = false;

    /**
     * @var string|null
     */
    public $idProperty;

    /**
     * @var array
     */
    public $property;

    /**
     * @var array
     */
    public $excludedEntityId = [];

    /**
     * @var array
     */
    public $searchSimple = [];

    /**
     * @var array
     */
    public $searchPattern = [];

    /**
     * @var array
     */
    public $orderBy = [];

    /**
     * @var array
     */
    public $where = [];

    /**
     * @var string|null
     */
    public $manager;

    /**
     * @var int|null
     */
    public $limit;

    /**
     * @var string|null
     */
    public $widget;

    /**
     * @var string|null
     */
    public $baseTemplate;

    /**
     * @var ToString|null
     */
    public $toString;

    /**
     * @var Cdn|null
     */
    public $cdn;

    /**
     * @var SelectOption|null
     */
    public $selectOption;

    /**
     * @var Security|null
     */
    public $security;

    /**
     * @var Repository|null
     */
    public $repository;

    /**
     * @var DependentSelects[]|null
     */
    public $dependentSelects = [];

    /**
     * @param array $parameters
     * @return self
     */
    public static function fromConfig(array $parameters): self
    {
        $self = new self();
        $self->name = $parameters['name'] ?? null;
        $self->class = $parameters['class'] ?? null;
        $self->rootAliase = $parameters['root_aliase'] ?? null;
        $self->multiple = $parameters['multiple'] ?? false;
        $self->idProperty = $parameters['id_property'] ?? null;
        $self->manager = $parameters['manager'] ?? null;
        $self->property = $parameters['property'] ?? null;
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
