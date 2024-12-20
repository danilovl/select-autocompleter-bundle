<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat;

use AllowDynamicProperties;
use Danilovl\SelectAutocompleterBundle\Exception\RuntimeException;
use Danilovl\SelectAutocompleterBundle\Model\Config\Config;
use Symfony\Component\PropertyAccess\PropertyAccess;

#[AllowDynamicProperties]
class Item
{
    /**
     * @param ItemChildren[] $children
     */
    public function __construct(
        public int|string|null $id,
        public string $text,
        public ?string $image = null,
        public ?array $children = null
    ) {
        if (empty($this->children) && $this->id === null) {
            throw new RuntimeException('If the item has no children, it must have an id');
        }

        if (!empty($this->children) && $this->id !== null) {
            throw new RuntimeException('If the item has children, it must not have an id');
        }

        if (!empty($this->children)) {
            foreach ($this->children as $child) {
                if (!$child instanceof ItemChildren) {
                    throw new RuntimeException('All children must be instances of ItemChildren');
                }
            }
        }
    }

    public static function formObject(
        object $object,
        Config $config
    ): self {
        /** @var string|int|null $id */
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
        $text = self::getToString($object, $config);

        /** @var string $result */
        $result = $text ?? $propertyAccess->getValue($object, $config->property);

        return $result;
    }

    private static function getToString(object $object, Config $config): ?string
    {
        $propertyAccess = PropertyAccess::createPropertyAccessor();
        $result = null;

        if ($config->toString->auto === true) {
            if (method_exists($object, '__toString')) {
                $result = (string) $object;
            }
        } elseif (!empty($config->toString->properties)) {
            $properties = [];
            foreach ($config->toString->properties as $property) {
                $properties[] = (string) $propertyAccess->getValue($object, $property);
            }

            $result = implode(' ', $properties);
            $format = $config->toString->format;
            if ($format !== null) {
                $result = sprintf($format, ...$properties);
            }
        }

        return $result;
    }
}
