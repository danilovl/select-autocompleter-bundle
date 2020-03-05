<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

use Danilovl\SelectAutocompleterBundle\Model\Interfaces\ChildItemInterface;
use Symfony\Component\HttpFoundation\Request;

class Repository implements ChildItemInterface
{
    public ?string $method = null;

    public static function fromConfig(array $parameters): self
    {
        $self = new self();
        $self->method = $parameters['method'] ?? null;

        return $self;
    }
}
