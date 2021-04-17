<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

use Danilovl\SelectAutocompleterBundle\Model\Interfaces\ChildItemInterface;
use Symfony\Component\HttpFoundation\Request;

class DependentSelects implements ChildItemInterface
{
    public ?string $name = null;
    public ?string $parentProperty = null;
    public ?string $parentField = null;
    public ?string $manyToMany = null;

    public static function fromConfig(array $parameters): self
    {
        $self = new self;
        $self->name = $parameters['name'] ?? null;
        $self->parentProperty = $parameters['parent_property'] ?? null;
        $self->parentField = $parameters['parent_field'] ?? null;
        $self->manyToMany = $parameters['many_to_many'] ?? null;

        return $self;
    }

    public static function fromArrayConfig(array $parameters): array
    {
        return array_map(fn(array $parameter): self => self::fromConfig($parameter), $parameters);
    }
}
