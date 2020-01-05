<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat;

use Danilovl\SelectAutocompleterBundle\Model\Config\Config;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Item
{
    /**
     * @var int|null
     */
    public $id;

    /**
     * @var string|null
     */
    public $text;

    /**
     * @param $object
     * @param Config $config
     * @return self
     */
    public static function formObject(
        $object,
        Config $config
    ): self {
        $item = new self;
        $item->id = (PropertyAccess::createPropertyAccessor())->getValue($object, $config->idProperty);
        $item->text = self::getText($object, $config);

        return $item;
    }

    /**
     * @param $object
     * @param Config $config
     * @return string
     */
    private static function getText(
        $object,
        Config $config
    ): string {
        $propertyAccess = PropertyAccess::createPropertyAccessor();
        $toString = $config->toString;

        $text = $propertyAccess->getValue($object, $config->property);
        if ($toString->auto === true) {
            $text = (string)$object;
        } elseif (!empty($toString->properties)) {
            $properties = [];
            foreach ($toString->properties as $property) {
                $properties[] = (string)$propertyAccess->getValue($object, $property);
            }

            $text = implode(' ', $properties);
            $format = $toString->format;
            if ($format !== null) {
                $text = sprintf($format, ...$properties);
            }
        }

        return $text;
    }
}
