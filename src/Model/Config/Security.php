<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

use Danilovl\SelectAutocompleterBundle\Constant\SecurityConditionConstant;
use Danilovl\SelectAutocompleterBundle\Model\Interfaces\ChildItemInterface;

class Security implements ChildItemInterface
{
    public ?string $voter = null;
    public array $role = [];
    public string $condition = SecurityConditionConstant::AND;

    public static function fromConfig(array $parameters): self
    {
        $self = new self;
        $self->voter = $parameters['voter'];
        $self->role = $parameters['role'];
        $self->condition = $parameters['condition'];

        return $self;
    }
}
