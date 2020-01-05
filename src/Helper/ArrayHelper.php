<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Helper;

use Danilovl\SelectAutocompleterBundle\Model\Interfaces\ChildItemInterface;

class ArrayHelper
{
    /**
     * @param $object
     * @return array
     */
    public static function modelToArray($object): array
    {
        $result = [];

        foreach ((array)$object as $key => $value) {
            $keyUnderscore = StringHelper::changeToUnderscore($key);
            $result[$keyUnderscore] = $value;

            if ($value instanceof ChildItemInterface) {
                $result[$keyUnderscore] = self::modelToArray($value);
            }
        }

        return $result;
    }

    /**
     * @param array $options
     * @return array
     */
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
