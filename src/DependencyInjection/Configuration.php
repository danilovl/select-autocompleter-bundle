<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\DependencyInjection;

use Danilovl\SelectAutocompleterBundle\Constant\{
    AuthenticatedVoterConstant,
    SearchConstant,
    OrderByConstant,
    SecurityConditionConstant,
    SelectOptionConstant
};
use Danilovl\SelectAutocompleterBundle\Model\Config\DefaultOption;
use Symfony\Component\Config\Definition\Builder\{
    TreeBuilder,
    ArrayNodeDefinition
};
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $defaultOption = DefaultOption::fromDefaultYaml();

        $treeBuilder = new TreeBuilder(AutocompleterExtension::ALIAS);
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('default_option')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('id_property')->defaultValue($defaultOption->idProperty)->end()
                        ->scalarNode('property')->defaultValue($defaultOption->property)->end()
                        ->scalarNode('image')->defaultNull()->end()
                        ->scalarNode('image_result_width')->defaultValue($defaultOption->imageResultWidth)->end()
                        ->scalarNode('image_selection_width')->defaultValue($defaultOption->imageSelectionWidth)->end()
                        ->scalarNode('root_alias')->defaultValue($defaultOption->rootAlias)->end()
                        ->scalarNode('limit')->defaultValue($defaultOption->limit)->end()
                        ->scalarNode('widget')->defaultValue($defaultOption->widget)->end()
                        ->scalarNode('manager')->defaultNull()->end()
                        ->scalarNode('base_template')->defaultValue($defaultOption->baseTemplate)->end()
                        ->arrayNode('order_by')
                            ->prototype('scalar')
                                ->validate()
                                    ->ifNotInArray(OrderByConstant::TYPES)
                                    ->thenInvalid('Available search types: ' . implode(',', OrderByConstant::TYPES))
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('where')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('to_string')
                            ->children()
                                ->scalarNode('auto')->defaultFalse()->end()
                                ->scalarNode('format')->defaultNull()->end()
                                ->arrayNode('properties')
                                    ->prototype('scalar')->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('select_option')
                            ->addDefaultsIfNotSet()
                            ->ignoreExtraKeys()
                            ->children()
                                ->scalarNode('placeholder')->defaultValue($defaultOption->selectOption->placeholder)->end()
                                ->scalarNode('dir')
                                    ->defaultValue(SelectOptionConstant::LTR)
                                    ->validate()
                                        ->ifNotInArray(SelectOptionConstant::DIRS)
                                        ->thenInvalid('Available search types: ' . implode(',', SelectOptionConstant::DIRS))
                                    ->end()
                                ->end()
                                ->scalarNode('delay')->defaultValue($defaultOption->selectOption->delay)->end()
                                ->scalarNode('minimum_input_length')->defaultValue(0)->end()
                                ->scalarNode('maximum_input_length')->defaultValue(0)->end()
                                ->scalarNode('minimum_results_for_search')->defaultValue(0)->end()
                                ->scalarNode('maximum_selection_length')->defaultValue(0)->end()
                                ->scalarNode('minimum_selection_length')->defaultValue(0)->end()
                                ->scalarNode('multiple')->defaultValue(false)->end()
                                ->scalarNode('width')->defaultValue($defaultOption->selectOption->width)->end()
                                ->scalarNode('scroll_after_select')->defaultFalse()->end()
                                ->scalarNode('language')->defaultValue($defaultOption->selectOption->language)->end()
                                ->scalarNode('select_on_close')->defaultFalse()->end()
                                ->scalarNode('theme')->defaultValue($defaultOption->selectOption->theme)->end()
                                ->scalarNode('amd_base')->defaultValue($defaultOption->selectOption->amdBase)->end()
                                ->scalarNode('amd_language_base')->defaultValue($defaultOption->selectOption->amdLanguageBase)->end()
                                ->scalarNode('cache')->defaultValue($defaultOption->selectOption->cache)->end()
                            ->end()
                        ->end()
                        ->arrayNode('cdn')
                            ->addDefaultsIfNotSet()
                            ->ignoreExtraKeys()
                            ->beforeNormalization()
                                ->always()
                                ->then(static function (array $cdn) use ($defaultOption): array {
                                    if (isset($cdn['auto']) && $cdn['auto'] === true) {
                                        $cdn['link'] = $defaultOption->cdn->link;
                                        $cdn['script'] = $defaultOption->cdn->script;
                                        $cdn['language'] = $defaultOption->cdn->language;
                                    } else {
                                        if (isset($cdn['link']) && $cdn['link'] === 'auto') {
                                            $cdn['link'] = $defaultOption->cdn->link;
                                        }

                                        if (isset($cdn['script']) && $cdn['script'] === 'auto') {
                                            $cdn['script'] = $defaultOption->cdn->script;
                                        }

                                        if (isset($cdn['language']) && $cdn['language'] === 'auto') {
                                            $cdn['language'] = $defaultOption->cdn->language;
                                        }
                                    }

                                    return $cdn;
                                })
                            ->end()
                            ->children()
                                ->scalarNode('auto')->defaultFalse()->end()
                                ->scalarNode('link')->defaultNull()->end()
                                ->scalarNode('script')->defaultNull()->end()
                                ->scalarNode('language')->defaultNull()->end()
                            ->end()
                        ->end()
                        ->arrayNode('security')
                            ->addDefaultsIfNotSet()
                            ->ignoreExtraKeys()
                            ->children()
                                ->scalarNode('public_access')->defaultFalse()->end()
                                ->scalarNode('voter')->defaultValue($defaultOption->security->voter)->end()
                                ->arrayNode('role')
                                    ->prototype('scalar')
                                        ->validate()
                                            ->ifTrue(static fn(string $role): bool => !str_contains($role, $defaultOption->rolePrefix) && !AuthenticatedVoterConstant::supportsAttribute($role))
                                            ->thenInvalid(sprintf('Roles must be start with prefix [%s] or some of reserved roles [%s]', $defaultOption->rolePrefix, implode(',', AuthenticatedVoterConstant::VOTERS)))
                                        ->end()
                                    ->end()
                                ->end()
                                ->scalarNode('condition')
                                    ->defaultValue($defaultOption->security->condition)
                                    ->validate()
                                        ->ifNotInArray(SecurityConditionConstant::TYPES)
                                        ->thenInvalid('Available condition types: ' . implode(',', SecurityConditionConstant::TYPES))
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('route')
                            ->addDefaultsIfNotSet()
                            ->ignoreExtraKeys()
                            ->children()
                                ->scalarNode('name')->defaultValue($defaultOption->route->name)->end()
                                ->arrayNode('parameters')
                                    ->defaultValue($defaultOption->route->parameters)
                                    ->prototype('scalar')->end()
                                ->end()
                                ->arrayNode('extra')
                                    ->defaultValue($defaultOption->route->extra)
                                    ->prototype('scalar')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        $this->addConfiguration($rootNode, 'orm', $defaultOption);
        $this->addConfiguration($rootNode, 'odm', $defaultOption);
        $this->addConfiguration($rootNode, 'own', $defaultOption);

        return $treeBuilder;
    }

    public function addConfiguration(
        ArrayNodeDefinition $rootNode,
        string $name,
        DefaultOption $defaultOption
    ): void {
        $rootNode
            ->children()
                ->arrayNode($name)
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')->isRequired()->end()
                            ->scalarNode('class')->isRequired()->end()
                            ->scalarNode('id_property')->end()
                            ->scalarNode('property')->end()
                            ->scalarNode('image')->end()
                            ->scalarNode('image_result_width')->end()
                            ->scalarNode('image_selection_width')->end()
                            ->scalarNode('root_alias')->end()
                            ->scalarNode('limit')->end()
                            ->scalarNode('compound')->defaultFalse()->end()
                            ->scalarNode('multiple')->defaultFalse()->end()
                            ->scalarNode('manager')->end()
                            ->arrayNode('search_simple')
                                ->prototype('scalar')
                                    ->treatNullLike(SearchConstant::ANY)
                                    ->validate()
                                        ->ifNotInArray(SearchConstant::TYPES)
                                        ->thenInvalid('Available search types: ' . implode(',', SearchConstant::TYPES))
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('search_pattern')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('order_by')
                                ->prototype('scalar')
                                    ->validate()
                                        ->ifNotInArray(OrderByConstant::TYPES)
                                        ->thenInvalid('Available search types: ' . implode(',', OrderByConstant::TYPES))
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('where')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('excluded_entity_id')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('to_string')
                                ->children()
                                    ->scalarNode('auto')->defaultFalse()->end()
                                    ->scalarNode('format')->defaultNull()->end()
                                    ->arrayNode('properties')
                                        ->prototype('scalar')->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->scalarNode('widget')->end()
                            ->scalarNode('base_template')->end()
                            ->arrayNode('select_option')
                                ->children()
                                    ->scalarNode('placeholder')->end()
                                    ->scalarNode('delay')->end()
                                    ->scalarNode('dir')
                                        ->validate()
                                            ->ifNotInArray(SelectOptionConstant::DIRS)
                                            ->thenInvalid('Available search types: ' . implode(',', SelectOptionConstant::DIRS))
                                        ->end()
                                    ->end()
                                    ->scalarNode('minimum_input_length')->end()
                                    ->scalarNode('maximum_input_length')->end()
                                    ->scalarNode('minimum_results_for_search')->end()
                                    ->scalarNode('maximum_selection_length')->end()
                                    ->scalarNode('minimum_selection_length')->end()
                                    ->scalarNode('multiple')->end()
                                    ->scalarNode('width')->end()
                                    ->scalarNode('scroll_after_select')->end()
                                    ->scalarNode('language')->end()
                                    ->scalarNode('select_on_close')->end()
                                    ->scalarNode('theme')->end()
                                    ->scalarNode('amd_base')->end()
                                    ->scalarNode('amd_language_base')->end()
                                    ->scalarNode('cache')->end()
                                ->end()
                            ->end()
                            ->arrayNode('security')
                                ->children()
                                    ->scalarNode('public_access')->end()
                                    ->scalarNode('voter')->end()
                                    ->arrayNode('role')
                                        ->prototype('scalar')
                                            ->validate()
                                                ->ifTrue(static fn(string $role): bool => !str_contains($role, $defaultOption->rolePrefix) && !AuthenticatedVoterConstant::supportsAttribute($role))
                                                ->thenInvalid(sprintf('Roles must be start with prefix [%s] or some of reserved roles [%s]', $defaultOption->rolePrefix, implode(',', AuthenticatedVoterConstant::VOTERS)))
                                            ->end()
                                        ->end()
                                    ->end()
                                    ->scalarNode('condition')
                                        ->validate()
                                            ->ifNotInArray(SecurityConditionConstant::TYPES)
                                            ->thenInvalid('Available condition types: ' . implode(',', SecurityConditionConstant::TYPES))
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('repository')
                                ->children()
                                    ->scalarNode('method')->end()
                                ->end()
                            ->end()
                            ->arrayNode('dependent_selects')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('name')->end()
                                        ->scalarNode('parent_property')->end()
                                        ->scalarNode('parent_field')->end()
                                        ->arrayNode('many_to_many')
                                            ->prototype('scalar')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('route')
                                ->children()
                                    ->scalarNode('name')->end()
                                    ->arrayNode('parameters')
                                        ->prototype('scalar')->end()
                                    ->end()
                                    ->arrayNode('extra')
                                        ->prototype('scalar')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
