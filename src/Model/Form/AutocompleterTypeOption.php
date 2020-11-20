<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Form;

use Danilovl\SelectAutocompleterBundle\Model\Config\{
    Cdn,
    DependentSelect,
    SelectOption
};

class AutocompleterTypeOption
{
    public ?string $name = null;
    public ?string $widget = null;
    public ?string $baseTemplate = null;
    public bool $compound = false;
    public bool $multiple = false;
    public array $extra = [];
    public ?SelectOption $selectOption = null;
    public ?Cdn $cdn = null;
    public ?DependentSelect $dependentSelect = null;

    public static function fromConfig(array $parameters): self
    {
        $self = new self;
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
