<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

use Danilovl\SelectAutocompleterBundle\Model\Interfaces\ChildItemInterface;

class Route implements ChildItemInterface
{
    public ?string $name = null;
    public array $parameters = [];
    public array $extra = [];

    public static function fromConfig(array $parameters): self
    {
        $self = new self;
        $self->name = $parameters['name'];
        $self->parameters = $parameters['parameters'];
        $self->extra = $parameters['extra'];

        return $self;
    }
}
