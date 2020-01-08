<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\DependencyInjection;

use Danilovl\SelectAutocompleterBundle\Constant\{
    SearchConstant,
    OrderByConstant,
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
    public const TREE_KEY_NAME = 'danilovl.select_autocompleter';

    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $defaultOption = DefaultOption::fromDefaultYaml();

        $treeBuilder = new TreeBuilder(self::TREE_KEY_NAME);
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('default_option')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('id_property')->defaultValue($defaultOption->idProperty)->end()
                        ->scalarNode('property')->defaultValue($defaultOption->property)->end()
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
                                ->scalarNode('placeholder')->defaultNull()->end()
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
                            ->beforeNormalization()
                                ->ifTrue(function (array $cdn): bool {
                                    return isset($cdn['auto']) && $cdn['auto'] === true;
                                })
                                ->then(function (array $cdn) use ($defaultOption): array {
                                    $cdn['link'] = $defaultOption->cdn->link;
                                    $cdn['script'] = $defaultOption->cdn->script;

                                    return $cdn;
                                })
                            ->end()
                            ->children()
                                ->scalarNode('auto')->defaultFalse()->end()
                                ->scalarNode('link')->defaultNull()->end()
                                ->scalarNode('script')->defaultNull()->end()
                            ->end()
                        ->end()
                        ->arrayNode('security')
                            ->addDefaultsIfNotSet()
                            ->ignoreExtraKeys()
                            ->children()
                                ->scalarNode('voter')->defaultValue($defaultOption->security->voter)->end()
                                ->arrayNode('role')
                                    ->prototype('scalar')
                                        ->validate()
                                            ->ifTrue(function (string $role) use ($defaultOption): bool {
                                                return strpos($role, $defaultOption->rolePrefix) === false;
                                            })
                                            ->thenInvalid(sprintf('Role must be start with %s', $defaultOption->rolePrefix))
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        $this->addConfiguration($rootNode, 'orm', $defaultOption);
        $this->addConfiguration($rootNode, 'odm', $defaultOption);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $rootNode
     * @param string $name
     * @param DefaultOption $defaultOption
     */
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
                            ->scalarNode('root_alias')->end()
                            ->scalarNode('limit')->end()
                            ->scalarNode('compound')->defaultFalse()->end()
                            ->scalarNode('multiple')->defaultFalse()->end()
                            ->scalarNode('extra')->defaultValue([])->end()
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
                                    ->scalarNode('voter')->end()
                                    ->arrayNode('role')
                                        ->prototype('scalar')
                                            ->validate()
                                                ->ifTrue(function (string $role) use ($defaultOption): bool {
                                                    return strpos($role, $defaultOption->rolePrefix) === false;
                                                })
                                                ->thenInvalid(sprintf('Role must be start with %s', $defaultOption->rolePrefix))
                                            ->end()
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
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
