<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Resolver\Config;

use Danilovl\SelectAutocompleterBundle\Model\Config\Config;
use Symfony\Component\OptionsResolver\{
    Options,
    OptionsResolver
};

class AutocompleterConfigResolver
{
    public function __construct(
        private readonly SelectOptionResolver $selectOptionResolver,
        private readonly CdnResolver $cdnResolver,
        private readonly ToStringResolver $toStringResolver,
        private readonly SecurityResolver $securityResolver,
        private readonly RepositoryResolver $repositoryResolver,
        private readonly DependentSelectsResolver $dependentSelectResolver,
        private readonly RouteResolver $routeResolver
    ) {}

    public function resolveConfig(array $options): Config
    {
        return Config::fromConfig($this->resolve($options));
    }

    public function resolve(array $options): array
    {
        $resolver = new OptionsResolver;
        $this->configureOptions($resolver, $options);

        return $resolver->resolve($options);
    }

    private function configureOptions(OptionsResolver $resolver, array $options): void
    {
        $defaults = [
            'name' => null,
            'class' => null,
            'id_property' => null,
            'property' => null,
            'property_search_type' => null,
            'image' => null,
            'image_result_width' => null,
            'image_selection_width' => null,
            'root_alias' => null,
            'where' => [],
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
        $this->routeResolver->configureOptions($resolver);

        if (isset($options['repository'])) {
            $this->repositoryResolver->configureOptions($resolver);
        }

        if (isset($options['dependent_selects'])) {
            $this->dependentSelectResolver->configureOptions($resolver);
        }

        $resolver
            ->setNormalizer('search_simple', static function (Options $options, array $value): array {
                if (empty($value) && !empty($options['property'])) {
                    $value = [
                        $options['property'] => $options['property_search_type']
                    ];
                }

                return $value;
            })
            ->setAllowedTypes('class', ['string', 'null'])
            ->setAllowedTypes('name', 'string')
            ->setAllowedTypes('id_property', ['string'])
            ->setAllowedTypes('property', ['string'])
            ->setAllowedTypes('property_search_type', ['string'])
            ->setAllowedTypes('image', ['string', 'null'])
            ->setAllowedTypes('image_result_width', ['string'])
            ->setAllowedTypes('image_selection_width', ['string'])
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
