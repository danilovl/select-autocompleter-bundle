<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;

class DefaultOption
{
    private const DIR_DEFAULT_CONFIG = '/../../Resources/config/default.yaml';

    public ?string $idProperty = null;
    public ?string $property = null;
    public ?string $rootAlias = null;
    public ?int $limit = null;
    public ?string $baseTemplate = null;
    public ?string $widget = null;
    public ?SelectOption $selectOption = null;
    public ?Cdn $cdn = null;
    public ?Security $security = null;
    public ?Security $rolePrefix = null;

    public static function fromDefaultYaml(): self
    {
        $yaml = Yaml::parseFile(__DIR__ . self::DIR_DEFAULT_CONFIG);

        return self::fromConfig($yaml['default']);
    }

    public static function fromConfig(array $parameters): self
    {
        $self = new self();
        $self->idProperty = $parameters['id_property'] ?? null;
        $self->property = $parameters['property'] ?? null;
        $self->rootAlias = $parameters['root_alias'] ?? null;
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
