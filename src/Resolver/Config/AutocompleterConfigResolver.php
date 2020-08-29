<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Resolver\Config;

use Danilovl\SelectAutocompleterBundle\Constant\SearchConstant;
use Danilovl\SelectAutocompleterBundle\Model\Config\Config;
use Symfony\Bridge\Doctrine\Form\ChoiceList\{
    EntityLoaderInterface,
    ORMQueryBuilderLoader
};
use Symfony\Component\OptionsResolver\{
    Options,
    OptionsResolver
};

class AutocompleterConfigResolver
{
    private SelectOptionResolver $selectOptionResolver;
    private CdnResolver $cdnResolver;
    private ToStringResolver $toStringResolver;
    private SecurityResolver $securityResolver;
    private RepositoryResolver $repositoryResolver;
    private DependentSelectsResolver $dependentSelectResolver;

    public function __construct(
        SelectOptionResolver $selectOptionResolver,
        CdnResolver $cdnResolver,
        ToStringResolver $toStringResolver,
        SecurityResolver $securityResolver,
        RepositoryResolver $repositoryResolver,
        DependentSelectsResolver $dependentSelectResolver
    ) {
        $this->selectOptionResolver = $selectOptionResolver;
        $this->cdnResolver = $cdnResolver;
        $this->toStringResolver = $toStringResolver;
        $this->securityResolver = $securityResolver;
        $this->repositoryResolver = $repositoryResolver;
        $this->dependentSelectResolver = $dependentSelectResolver;
    }

    public function resolveConfig(array $options): Config
    {
        return Config::fromConfig($this->resolve($options));
    }

    public function resolve(array $options): array
    {
        $resolver = new OptionsResolver;
        $this->configureOptions($resolver);

        return $resolver->resolve($options);
    }

    private function configureOptions(OptionsResolver $resolver): void
    {
        $defaults = [
            'name' => null,
            'id_property' => null,
            'property' => null,
            'image' => null,
            'image_result_width' => null,
            'image_selection_width' => null,
            'root_alias' => null,
            'where' => [],
            'order_by' => [],
            'excluded_entity_id' => [],
            'limit' => null,
            'manager' => null,
            'search_simple' => [],
            'search_pattern' => [],
            'order_by' => [],
            'compound' => false,
            'multiple' => false,
            'extra' => [],
            'widget' => null,
            'base_template' => null,
        ];

        $resolver->setDefaults($defaults);

        $this->selectOptionResolver->configureOptions($resolver);
        $this->cdnResolver->configureOptions($resolver);
        $this->toStringResolver->configureOptions($resolver);
        $this->securityResolver->configureOptions($resolver);
        $this->repositoryResolver->configureOptions($resolver);
        $this->dependentSelectResolver->configureOptions($resolver);

        $resolver
            ->setNormalizer('search_simple', function (Options $options, $value) {
                if (empty($value) && !empty($options['property'])) {
                    $value = [
                        $options['property'] => SearchConstant::ANY
                    ];
                }

                return $value;
            })
            ->setRequired(['class'])
            ->setAllowedTypes('name', 'string')
            ->setAllowedTypes('id_property', 'string')
            ->setAllowedTypes('property', 'string')
            ->setAllowedTypes('image', ['string', 'null'])
            ->setAllowedTypes('image_result_width', ['string', 'null'])
            ->setAllowedTypes('image_selection_width', ['string', 'null'])
            ->setAllowedTypes('root_alias', 'string')
            ->setAllowedTypes('where', 'array')
            ->setAllowedTypes('order_by', 'array')
            ->setAllowedTypes('excluded_entity_id', 'array')
            ->setAllowedTypes('limit', 'integer')
            ->setAllowedTypes('manager', ['string', 'null'])
            ->setAllowedTypes('widget', 'string')
            ->setAllowedTypes('base_template', 'string')
            ->setAllowedTypes('search_simple', 'array')
            ->setAllowedTypes('search_pattern', 'array')
            ->setAllowedTypes('order_by', 'array')
            ->setAllowedTypes('compound', 'bool')
            ->setAllowedTypes('multiple', 'bool')
            ->setAllowedTypes('extra', 'array');
    }
}