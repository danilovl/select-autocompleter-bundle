<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

use Danilovl\SelectAutocompleterBundle\Model\Interfaces\ChildItemInterface;
use Symfony\Component\HttpFoundation\Request;

class SelectOption implements ChildItemInterface
{
    public ?int $delay = null;
    public ?string $dir = null;
    public ?int $minimumInputLength = null;
    public ?int $maximumInputLength = null;
    public ?int $minimumResultsForSearch = null;
    public ?int $maximumSelectionLength = null;
    public ?int $minimumSelectionLength = null;
    public bool $multiple = false;
    public ?string $placeholder = null;
    public ?string $width = null;
    public bool $scrollAfterSelect = false;
    public bool $selectOnClose = false;
    public ?string $language = null;
    public ?string $theme = null;
    public ?string $amdBase = null;
    public ?string $amdLanguageBase = null;
    public bool $cache = false;

    public static function fromConfig(array $parameters): self
    {
        $self = new self;
        $self->placeholder = $parameters['placeholder'] ?? null;
        $self->delay = $parameters['delay'] ?? null;
        $self->dir = $parameters['dir'] ?? null;
        $self->minimumInputLength = $parameters['minimum_input_length'] ?? null;
        $self->maximumInputLength = $parameters['maximum_input_length'] ?? null;
        $self->minimumResultsForSearch = $parameters['minimum_results_for_search'] ?? null;
        $self->maximumSelectionLength = $parameters['maximum_selection_length'] ?? null;
        $self->minimumSelectionLength = $parameters['minimum_selection_length'] ?? null;
        $self->multiple = $parameters['multiple'] ?? false;
        $self->width = $parameters['width'] ?? null;
        $self->scrollAfterSelect = $parameters['scroll_after_select'] ?? false;
        $self->language = $parameters['language'] ?? null;
        $self->selectOnClose = $parameters['select_on_close'] ?? false;
        $self->theme = $parameters['theme'] ?? null;
        $self->amdBase = $parameters['amd_base'] ?? null;
        $self->amdLanguageBase = $parameters['amd_language_base'] ?? null;
        $self->cache = $parameters['cache'] ?? false;

        return $self;
    }
}
