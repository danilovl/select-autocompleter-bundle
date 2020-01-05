<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

use Danilovl\SelectAutocompleterBundle\Model\Interfaces\ChildItemInterface;
use Symfony\Component\HttpFoundation\Request;

class Security implements ChildItemInterface
{
    /**
     * @var string|null
     */
    public $voter;

    /**
     * @var array|null
     */
    public $role;

    /**
     * @param array $parameters
     * @return self
     */
    public static function fromConfig(array $parameters): self
    {
        $self = new self();
        $self->voter = $parameters['voter'] ?? null;
        $self->role = $parameters['role'] ?? null;

        return $self;
    }
}
