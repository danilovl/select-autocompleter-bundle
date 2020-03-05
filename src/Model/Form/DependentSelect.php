<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Form;

use Danilovl\SelectAutocompleterBundle\Model\Interfaces\ChildItemInterface;
use Symfony\Component\HttpFoundation\Request;

class DependentSelect implements ChildItemInterface
{
    public ?string $name = null;
    public ?string $parentField = null;

    public static function fromConfig(array $parameters): self
    {
        $self = new self();
        $self->name = $parameters['name'] ?? null;
        $self->parentField = $parameters['parent_field'] ?? null;

        return $self;
    }
}
