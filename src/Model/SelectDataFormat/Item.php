<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat;

use AllowDynamicProperties;
use Danilovl\SelectAutocompleterBundle\Model\Config\Config;
use Symfony\Component\PropertyAccess\PropertyAccess;

#[AllowDynamicProperties]
class Item
{
    private function __construct(
        public mixed $id,
        public ?string $text,
        public ?string $image
    ) {}

    public static function formObject(
        object $object,
        Config $config
    ): self {
        $id = (PropertyAccess::createPropertyAccessor())->getValue($object, $config->idProperty);
        /** @var string|null $image */
        $image = $config->image !== null ? (PropertyAccess::createPropertyAccessor())->getValue($object, $config->image) : null;

        return new self(
            $id,
            self::getText($object, $config),
            $image
        );
    }

    private static function getText(
        object $object,
        Config $config
    ): string {
        $propertyAccess = PropertyAccess::createPropertyAccessor();
        $toString = $config->toString;

        $text = null;
        if ($toString->auto === true) {
            if (method_exists($object , '__toString')) {
                $text = (string) $object;
            }
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

        /** @var string $result */
        $result =  $text ?? $propertyAccess->getValue($object, $config->property);

        return $result;
    }
}
