<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat;

use Danilovl\SelectAutocompleterBundle\Model\Config\Config;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Item
{
    public ?int $id = null;
    public ?string $text = null;
    public ?string $image = null;

    public static function formObject(
        object $object,
        Config $config
    ): self {
        $item = new self;
        $item->id = (PropertyAccess::createPropertyAccessor())->getValue($object, $config->idProperty);
        $item->text = self::getText($object, $config);
        $item->image = $config->image !== null ? (PropertyAccess::createPropertyAccessor())->getValue($object, $config->image) : null;

        return $item;
    }

    private static function getText(
        object $object,
        Config $config
    ): string {
        $propertyAccess = PropertyAccess::createPropertyAccessor();
        $toString = $config->toString;

        $text = null;
        if ($toString->auto === true) {
            $text = (string) $object;
        } elseif (!empty($toString->properties)) {
            $properties = [];
            foreach ($toString->properties as $property) {
                $properties[] = (string) $propertyAccess->getValue($object, $property);
            }

            $text = implode(' ', $properties);
            $format = $toString->format;
            if ($format !== null) {
                $text = sprintf($format, ...$properties);
            }
        }

        return $text ?? $propertyAccess->getValue($object, $config->property);
    }
}
