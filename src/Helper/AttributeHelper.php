<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Helper;

use ReflectionClass;

class AttributeHelper
{
    public static function getInstance(string $class, string $attribute): ?object
    {
        $reflectionClass = new ReflectionClass($class);
        $attributes = $reflectionClass->getAttributes($attribute);

        if (count($attributes) === 0) {
            return null;
        }

        return $attributes[0]->newInstance();
    }
}
