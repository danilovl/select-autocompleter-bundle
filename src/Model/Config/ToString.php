<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

use Danilovl\SelectAutocompleterBundle\Model\Interfaces\ChildItemInterface;

class ToString implements ChildItemInterface
{
    public bool $auto = false;
    public ?string $format = null;
    public array $properties = [];

    public static function fromConfig(array $parameters): self
    {
        $self = new self;
        $self->auto = $parameters['auto'] ?? false;
        $self->format = $parameters['format'] ?? null;
        $self->properties = $parameters['properties'] ?? [];

        return $self;
    }
}
