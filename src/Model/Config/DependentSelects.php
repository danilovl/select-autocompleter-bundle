<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\Config;

use Danilovl\SelectAutocompleterBundle\Model\Interfaces\ChildItemInterface;
use Symfony\Component\HttpFoundation\Request;

class DependentSelects implements ChildItemInterface
{
    /**
     * @var string|null
     */
    public $name;

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
        $self->name = $parameters['name'] ?? null;
        $self->parentProperty = $parameters['parent_property'] ?? null;
        $self->parentField = $parameters['parent_field'] ?? null;
        $self->manyToMany = $parameters['many_to_many'] ?? null;

        return $self;
    }

    /**
     * @param array $parameters
     * @return DependentSelects[]
     */
    public static function fromArrayConfig(array $parameters): array
    {
        return array_map(static function (array $parameter): self {
            return self::fromConfig($parameter);
        }, $parameters);
    }
}
