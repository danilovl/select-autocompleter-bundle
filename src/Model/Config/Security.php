<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

use Danilovl\SelectAutocompleterBundle\Model\Interfaces\ChildItemInterface;

class Security implements ChildItemInterface
{
    public ?string $voter = null;
    public ?array $role = null;

    public static function fromConfig(array $parameters): self
    {
        $self = new self;
        $self->voter = $parameters['voter'] ?? null;
        $self->role = $parameters['role'] ?? null;

        return $self;
    }
}
