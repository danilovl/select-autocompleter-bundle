<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

use Danilovl\SelectAutocompleterBundle\Model\Interfaces\ChildItemInterface;
use Symfony\Component\HttpFoundation\Request;

class SelectOption implements ChildItemInterface
{
    /**
     * @var int|null
     */
    public $delay;

    /**
     * @var string|null
     */
    public $dir;

    /**
     * @var int|null
     */
    public $minimumInputLength;

    /**
     * @var int|null
     */
    public $maximumInputLength;

    /**
     * @var int|null
     */
    public $minimumResultsForSearch;

    /**
     * @var int|null
     */
    public $maximumSelectionLength;

    /**
     * @var int|null
     */
    public $minimumSelectionLength;

    /**
     * @var bool
     */
    public $multiple = false;

    /**
     * @var string|null
     */
    public $placeholder;

    /**
     * @var bool|null
     */
    public $width;

    /**
     * @var bool
     */
    public $scrollAfterSelect = false;

    /**
     * @var bool
     */
    public $selectOnClose = false;

    /**
     * @var string|null
     */
    public $language;

    /**
     * @var string|null
     */
    public $theme;

    /**
     * @var string|null
     */
    public $amdBase;

    /**
     * @var string|null
     */
    public $amdLanguageBase;

    /**
     * @var bool
     */
    public $cache = false;

    /**
     * @param array $parameters
     * @return self
     */
    public static function fromConfig(array $parameters): self
    {
        $self = new self();
        $self->placeholder = $parameters['placeholder'] ?? null;
        $self->delay = $parameters['delay'] ?? null;
        $self->dir = $parameters['dir'] ?? null;
        $self->minimumInputLength = $parameters['minimum_input_length'] ?? null;
        $self->maximumInputLength = $parameters['maximum_input_length'] ?? null;
        $self->minimumResultsForSearch = $parameters['minimum_results_for_search'] ?? null;
        $self->maximumSelectionLength = $parameters['maximum_selection_length'] ?? null;
        $self->minimumSelectionLength = $parameters['minimum_selection_length'] ?? null;
        $self->multiple = $parameters['multiple'] ?? null;
        $self->width = $parameters['width'] ?? null;
        $self->scrollAfterSelect = $parameters['scroll_after_select'] ?? null;
        $self->language = $parameters['language'] ?? null;
        $self->selectOnClose = $parameters['select_on_close'] ?? null;
        $self->theme = $parameters['theme'] ?? null;
        $self->amdBase = $parameters['amd_base'] ?? null;
        $self->amdLanguageBase = $parameters['amd_language_base'] ?? null;
        $self->cache = $parameters['cache'] ?? false;

        return $self;
    }
}
