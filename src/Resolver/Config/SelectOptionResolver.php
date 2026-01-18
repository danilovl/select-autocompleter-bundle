<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Resolver\Config;

use Closure;
use Danilovl\SelectAutocompleterBundle\Model\Config\SelectOption;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectOptionResolver
{
    public function configureOptions(
        OptionsResolver $resolver,
        ?SelectOption $selectOption = null
    ): void {
        $resolver->setOptions(
            option: 'select_option',
            nested: $this->getConfigureOptions($selectOption ?? new SelectOption)
        );
    }

    public function getConfigureOptions(SelectOption $selectOptions): Closure
    {
        return static function (OptionsResolver $resolver) use ($selectOptions): void {
            $resolver
                ->setDefaults([
                    'placeholder' => $selectOptions->placeholder,
                    'delay' => $selectOptions->delay,
                    'dir' => $selectOptions->dir,
                    'minimum_input_length' => $selectOptions->minimumInputLength,
                    'maximum_input_length' => $selectOptions->maximumInputLength,
                    'minimum_results_for_search' => $selectOptions->minimumResultsForSearch,
                    'maximum_selection_length' => $selectOptions->maximumSelectionLength,
                    'minimum_selection_length' => $selectOptions->minimumSelectionLength,
                    'multiple' => $selectOptions->multiple,
                    'width' => $selectOptions->width,
                    'scroll_after_select' => $selectOptions->scrollAfterSelect,
                    'language' => $selectOptions->language,
                    'select_on_close' => $selectOptions->selectOnClose,
                    'theme' => $selectOptions->theme,
                    'amd_base' => $selectOptions->amdBase,
                    'amd_language_base' => $selectOptions->amdLanguageBase,
                    'cache' => $selectOptions->cache
                ])
                ->setAllowedTypes('placeholder', ['string', 'null'])
                ->setAllowedTypes('delay', ['integer', 'null'])
                ->setAllowedTypes('dir', ['string', 'null'])
                ->setAllowedTypes('minimum_input_length', ['integer', 'null'])
                ->setAllowedTypes('maximum_input_length', ['integer', 'null'])
                ->setAllowedTypes('minimum_results_for_search', ['integer', 'null'])
                ->setAllowedTypes('maximum_selection_length', ['integer', 'null'])
                ->setAllowedTypes('minimum_selection_length', ['integer', 'null'])
                ->setAllowedTypes('multiple', ['bool', 'null'])
                ->setAllowedTypes('width', ['string', 'null'])
                ->setAllowedTypes('scroll_after_select', 'bool')
                ->setAllowedTypes('select_on_close', 'bool')
                ->setAllowedTypes('language', ['string', 'null'])
                ->setAllowedTypes('theme', ['string', 'null'])
                ->setAllowedTypes('amd_base', ['string', 'null'])
                ->setAllowedTypes('amd_language_base', ['string', 'null'])
                ->setAllowedTypes('cache', 'bool');
        };
    }
}
