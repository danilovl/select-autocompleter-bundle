<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

use Danilovl\SelectAutocompleterBundle\Model\Interfaces\ChildItemInterface;

class DependentSelect implements ChildItemInterface
{
    public ?string $parentProperty = null;
    public ?string $parentField = null;
    public ?string $manyToMany = null;

    public static function fromConfig(array $parameters): self
    {
        $self = new self;
        $self->parentProperty = $parameters['parent_property'] ?? null;
        $self->parentField = $parameters['parent_field'] ?? null;
        $self->manyToMany = $parameters['many_to_many'] ?? null;

        return $self;
    }
}
