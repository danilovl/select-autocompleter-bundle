<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Type;

use Danilovl\SelectAutocompleterBundle\Model\Config\{
    Cdn,
    DependentSelect,
    SelectOption
};

class AutocompleterTypeOption
{
    /**
     * @var string|null
     */
    public $name;

    /**
     * @var string|null
     */
    public $widget;

    /**
     * @var string|null
     */
    public $baseTemplate;

    /**
     * @var bool
     */
    public $compound = false;

    /**
     * @var bool
     */
    public $multiple = false;

    /**
     * @var array
     */
    public $extra = [];

    /**
     * @var SelectOption
     */
    public $selectOption;

    /**
     * @var Cdn
     */
    public $cdn;

    /**
     * @var DependentSelect
     */
    public $dependentSelect;

    /**
     * @param array $parameters
     * @return self
     */
    public static function fromConfig(array $parameters): self
    {
        $self = new self();
        $self->name = $parameters['name'] ?? null;
        $self->widget = $parameters['widget'] ?? null;
        $self->baseTemplate = $parameters['base_template'] ?? null;
        $self->compound = $parameters['compound'] ?? false;
        $self->multiple = $parameters['multiple'] ?? false;
        $self->extra = $parameters['extra'] ?? [];
        $self->selectOption = SelectOption::fromConfig($parameters['select_option'] ?? []);
        $self->cdn = Cdn::fromConfig($parameters['cdn'] ?? []);
        $self->dependentSelect = DependentSelect::fromConfig($parameters['dependent_select'] ?? []);

        return $self;
    }
}
