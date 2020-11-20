<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

use Danilovl\SelectAutocompleterBundle\Model\Interfaces\ChildItemInterface;
use Symfony\Component\HttpFoundation\Request;

class Attr implements ChildItemInterface
{
    public ?string $placeholder = null;

    public static function fromConfig(array $parameters): self
    {
        $self = new self;
        $self->placeholder = $parameters['placeholder'] ?? null;

        return $self;
    }
}
