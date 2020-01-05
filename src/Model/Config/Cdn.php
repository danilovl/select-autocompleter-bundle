<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

use Danilovl\SelectAutocompleterBundle\Model\Interfaces\ChildItemInterface;
use Symfony\Component\HttpFoundation\Request;

class Cdn implements ChildItemInterface
{
    /**
     * @var bool
     */
    public $auto = false;

    /**
     * @var string|null
     */
    public $link;

    /**
     * @var string|null
     */
    public $script;

    /**
     * @param array $parameters
     * @return self
     */
    public static function fromConfig(array $parameters): self
    {
        $self = new self();
        $self->auto = $parameters['auto'] ?? null;
        $self->link = $parameters['link'] ?? null;
        $self->script = $parameters['script'] ?? null;

        return $self;
    }
}
