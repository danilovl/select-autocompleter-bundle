<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

use Danilovl\SelectAutocompleterBundle\Model\Interfaces\ChildItemInterface;

class Cdn implements ChildItemInterface
{
    public bool $auto = false;
    public ?string $link = null;
    public ?string $script = null;

    public static function fromConfig(array $parameters): self
    {
        $self = new self;
        $self->auto = $parameters['auto'] ?? false;
        $self->link = $parameters['link'] ?? null;
        $self->script = $parameters['script'] ?? null;

        return $self;
    }
}
