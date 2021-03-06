<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Form\DataTransformer;

use Danilovl\SelectAutocompleterBundle\Helper\ArrayHelper;
use Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat\Item;
use Danilovl\SelectAutocompleterBundle\Services\Interfaces\AutocompleterInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class AutocompleterTransformer implements DataTransformerInterface
{
    public function __construct(
        private AutocompleterInterface $autocompleter,
        private bool $isMultiple = false
    ) {
    }

    public function transform(mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($this->isMultiple && !($value instanceof iterable)) {
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

    public function reverseTransform(mixed $id): mixed
    {
        if ($id === null) {
            return $this->isMultiple ? [] : null;
        }

        if ($this->isMultiple && is_string($id)) {
            $id = array_map('trim', explode(',', $id));
        } else {
            $id = [$id];
        }

        $result = $this->autocompleter->reverseTransform($id);
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
