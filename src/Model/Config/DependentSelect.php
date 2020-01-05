<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

use Danilovl\SelectAutocompleterBundle\Model\Interfaces\ChildItemInterface;
use Symfony\Component\HttpFoundation\Request;

class DependentSelect implements ChildItemInterface
{
    /**
     * @var string|null
     */
    public $parentProperty;  
    
    /**
     * @var string|null
     */
    public $parentField; 
    
    /**
     * @var string|null
     */
    public $manyToMany;

    /**
     * @param array $parameters
     * @return self
     */
    public static function fromConfig(array $parameters): self
    {
        $self = new self();
        $self->parentProperty = $parameters['parent_property'] ?? null;
        $self->parentField = $parameters['parent_field'] ?? null;
        $self->manyToMany = $parameters['many_to_many'] ?? null;

        return $self;
    }
}
