<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

use Danilovl\SelectAutocompleterBundle\Model\Interfaces\ChildItemInterface;
use Symfony\Component\HttpFoundation\Request;

class Attr implements ChildItemInterface
{
    /**
     * @var string|null
     */
    public $placeholder;

    /**
     * @param array $parameters
     * @return self
     */
    public static function fromConfig(array $parameters): self
    {
        $self = new self();
        $self->placeholder = $parameters['placeholder'] ?? null;

        return $self;
    }
}
