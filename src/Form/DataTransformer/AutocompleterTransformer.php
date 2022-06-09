<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Form\DataTransformer;

use Danilovl\SelectAutocompleterBundle\Helper\ArrayHelper;
use Danilovl\SelectAutocompleterBundle\Interfaces\AutocompleterInterface;
use Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat\Item;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Traversable;

class AutocompleterTransformer implements DataTransformerInterface
{
    public function __construct(
        private readonly AutocompleterInterface $autocompleter,
        private readonly bool $isMultiple = false
    ) {
    }

    public function transform(mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($this->isMultiple && !($value instanceof Traversable)) {
            throw new TransformationFailedException;
        }

        $value = !$this->isMultiple ? [$value] : $value;

        $items = $this->autocompleter->transformObjectsToItem($value);
        $result = array_map(fn(Item $item): array => $this->transformItemToArray($item), $items);

        if ($this->isMultiple) {
            return $result;
        }

        return $result[0] ?? null;
    }

    public function reverseTransform(mixed $value): mixed
    {
        if ($value === null) {
            return $this->isMultiple ? [] : null;
        }

        if ($this->isMultiple && is_string($value)) {
            $value = array_map('trim', explode(',', $value));
        } else {
            $value = [$value];
        }

        $result = $this->autocompleter->reverseTransform($value);
        if ($this->isMultiple) {
            return $result;
        }

        return $result[0] ?? null;
    }

    protected function transformItemToArray(Item $item): array
    {
        return ArrayHelper::modelToArray($item);
    }
}
