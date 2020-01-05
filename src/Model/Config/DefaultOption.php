<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;

class DefaultOption
{
    private const DIR_DEFAULT_CONFIG = '/../../Resources/config/default.yaml';

    /**
     * @var string|null
     */
    public $idProperty;

    /**
     * @var string|null
     */
    public $property;
    
    /**
     * @var string|null
     */
    public $rootAliase;

    /**
     * @var int|null
     */
    public $limit;

    /**
     * @var string|null
     */
    public $baseTemplate;

    /**
     * @var string|null
     */
    public $widget;

    /**
     * @var SelectOption|null
     */
    public $selectOption;

    /**
     * @var Cdn|null
     */
    public $cdn;

    /**
     * @var Security|null
     */
    public $security;
    
    /**
     * @var Security|null
     */
    public $rolePrefix;

    /**
     * @return self
     */
    public static function fromDefaultYaml(): self
    {
        $yaml = Yaml::parseFile(__DIR__ . self::DIR_DEFAULT_CONFIG);

        return self::fromConfig($yaml['default']);
    }

    /**
     * @param array $parameters
     * @return self
     */
    public static function fromConfig(array $parameters): self
    {
        $self = new self();
        $self->idProperty = $parameters['id_property'] ?? null;
        $self->property = $parameters['property'] ?? null;
        $self->rootAliase = $parameters['root_aliase'] ?? null;
        $self->limit = $parameters['limit'] ?? null;
        $self->baseTemplate = $parameters['base_template'] ?? null;
        $self->widget = $parameters['widget'] ?? null;
        $self->rolePrefix = $parameters['role_prefix'] ?? null;
        $self->selectOption = SelectOption::fromConfig($parameters['select_option'] ?? []);
        $self->cdn = Cdn::fromConfig($parameters['cdn'] ?? []);
        $self->security = Security::fromConfig($parameters['security'] ?? []);

        return $self;
    }
}
