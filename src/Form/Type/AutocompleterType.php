<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Form\Type;

use Danilovl\SelectAutocompleterBundle\Form\DataTransformer\AutocompleterTransformer;
use Danilovl\SelectAutocompleterBundle\Helper\ArrayHelper;
use Danilovl\SelectAutocompleterBundle\Resolver\Form\AutocompleterTypeResolver;
use Danilovl\SelectAutocompleterBundle\Services\Interfaces\AutocompleterContainerInterface;
use Symfony\Component\Form\{
    FormEvent,
    FormEvents,
    FormView,
    AbstractType,
    FormInterface,
    FormBuilderInterface
};
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\{
    Options,
    OptionsResolver
};
use Twig\Environment;

class AutocompleterType extends AbstractType
{
    public const NAME = 'select_autocompleter';

    /**
     * @var AutocompleterContainerInterface
     */
    private $autocompleterContainer;

    /**
     * @var AutocompleterTypeResolver
     */
    private $autocompleterTypeResolver;

    /**
     * @var Environment
     */
    private $environment;

    /**
     * @param AutocompleterContainerInterface $autocompleterContainer
     * @param AutocompleterTypeResolver $autocompleterTypeResolver
     * @param Environment $environment
     */
    public function __construct(
        AutocompleterContainerInterface $autocompleterContainer,
        AutocompleterTypeResolver $autocompleterTypeResolver,
        Environment $environment
    ) {
        $this->autocompleterContainer = $autocompleterContainer;
        $this->autocompleterTypeResolver = $autocompleterTypeResolver;
        $this->environment = $environment;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $autocompleter = $this->autocompleterContainer->get($options['autocompleter']['name']);

        $builder->addViewTransformer(new AutocompleterTransformer($autocompleter, $options['autocompleter']['multiple']));
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(
        FormView $view,
        FormInterface $form,
        array $options
    ): void {
        $view->vars['autocompleter'] = $this->configurateOptionsByLevels($form);
        $this->addNameBlockPrefixes($view);

        $value = $view->vars['value'];
        $values = [];
        if ($value !== null) {
            $values = $options['autocompleter']['multiple'] ? $value : [$value];
        }

        $ids = array_map(function ($item) {
            return $item['id'];
        }, $values);

        $view->vars['autocompleter']['ids'] = $ids;
        $view->vars['autocompleter']['values'] = $values;

        $this->environment->addGlobal('autocompleter_base_template', $view->vars['autocompleter']['base_template']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $this->autocompleterTypeResolver->configureOptions($resolver);
    }

    /**
     * @param FormInterface $form
     * @return array
     */
    private function configurateOptionsByLevels(FormInterface $form): array
    {
        $passedOptions = $form->getConfig()->getAttribute('data_collector/passed_options')['autocompleter'];

        $autocompleter = $this->autocompleterContainer->get($passedOptions['name']);
        $autocompleterConfig = ArrayHelper::modelToArray($autocompleter->getConfig());

        return array_replace_recursive($autocompleterConfig, $passedOptions);
    }

    /**
     * @param FormView $view
     */
    private function addNameBlockPrefixes(FormView $view): void
    {
        array_splice(
            $view->vars['block_prefixes'],
            array_search($this->getBlockPrefix(), $view->vars['block_prefixes']) + 1, 0,
            $view->vars['autocompleter']['name'] . '_autocompleter'
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return self::NAME;
    }
}