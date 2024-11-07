<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Proxy;

use Danilovl\SelectAutocompleterBundle\Helper\ArrayHelper;
use Danilovl\SelectAutocompleterBundle\Interfaces\AutocompleterContainerInterface;
use Symfony\Component\Form\{
    FormTypeInterface,
    ResolvedFormType,
    FormBuilderInterface,
    FormFactoryInterface,
    ResolvedFormTypeInterface
};

class AutocompeleterResolvedFormType extends ResolvedFormType
{
    public function __construct(
        private readonly AutocompleterContainerInterface $autocompleterContainer,
        FormTypeInterface $type,
        array $typeExtensions,
        ResolvedFormTypeInterface $parent = null
    ) {
        parent::__construct($type, $typeExtensions, $parent);
    }

    public function createBuilder(FormFactoryInterface $factory, string $name, array $options = []): FormBuilderInterface
    {
        $builder = parent::createBuilder($factory, $name, $options);

        if (isset($options['autocompleter'])) {
            $builder->setAttribute('autocompleter/passed_options', $options);
            $builder->setType($this);

            $this->updateConfig($options);
        }

        return $builder;
    }

    /**
     * @param array{autocompleter: array{name: string, dependent_select: array}} $options
     */
    private function updateConfig(array $options): void
    {
        $passedOptions = $options['autocompleter'];
        $autocompleter = $this->autocompleterContainer->get($passedOptions['name']);

        unset($passedOptions['name']);
        unset($passedOptions['dependent_select']);

        $autocompleterConfig = ArrayHelper::modelToArray($autocompleter->getConfig());
        $newConfig = array_replace_recursive($autocompleterConfig, $passedOptions);

        $autocompleter->addConfig($newConfig);
        $autocompleter->isUpdateConfigByResolvedFormType(true);
    }
}
