<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

use Danilovl\SelectAutocompleterBundle\Model\Interfaces\ChildItemInterface;
use Symfony\Component\HttpFoundation\Request;

class Repository implements ChildItemInterface
{
    /**
     * @var string|null
     */
    public $method;

    /**
     * @param array $parameters
     * @return self
     */
    public static function fromConfig(array $parameters): self
    {
        $self = new self();
        $self->method = $parameters['method'] ?? null;

        return $self;
    }
}
