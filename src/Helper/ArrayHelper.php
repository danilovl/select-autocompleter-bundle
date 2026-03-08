<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Helper;

use Danilovl\SelectAutocompleterBundle\Model\Interfaces\ChildItemInterface;

class ArrayHelper
{
    public static function modelToArray(mixed $object): array
    {
        $result = [];

        foreach ((array) $object as $key => $value) {
            $keyUnderscore = StringHelper::changeToUnderscore($key);

            if ($value instanceof ChildItemInterface) {
                $result[$keyUnderscore] = self::modelToArray($value);
            } elseif (is_array($value)) {
                $result[$keyUnderscore] = self::handleArray($value);
            } else {
                $result[$keyUnderscore] = $value;
            }
        }

        return $result;
    }

    private static function handleArray(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            if ($value instanceof ChildItemInterface) {
                $result[$key] = self::modelToArray($value);
            } elseif (is_array($value)) {
                $result[$key] = self::handleArray($value);
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    public static function removeEmptyValues(array $options): array
    {
        $result = [];
        foreach ($options as $key => $value) {
            if (is_array($value)) {
                $result[$key] = self::removeEmptyValues($value);
            } else {
                if (!empty($value)) {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }
}
