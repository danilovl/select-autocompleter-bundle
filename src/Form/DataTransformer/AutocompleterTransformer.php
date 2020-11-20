<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Form\DataTransformer;

use Danilovl\SelectAutocompleterBundle\Helper\ArrayHelper;
use Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat\Item;
use Danilovl\SelectAutocompleterBundle\Services\Interfaces\AutocompleterInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class AutocompleterTransformer implements DataTransformerInterface
{
    private AutocompleterInterface $autocompleter;
    private bool $isMultiple;

    /**
     * @param AutocompleterInterface $autocompleter
     * @param bool $multiple
     */
    public function __construct(
        AutocompleterInterface $autocompleter,
        bool $multiple = false
    ) {
        $this->autocompleter = $autocompleter;
        $this->isMultiple = $multiple;
    }

    public function transform($value)
    {
        if ($value === null) {
            return null;
        }

        if ($this->isMultiple && !($value instanceof iterable)) {
            throw new TransformationFailedException;
        }

        $value = !$this->isMultiple ? [$value] : $value;

        $result = array_map(function (Item $item): array {
            return $this->transformItemToArray($item);
        }, $this->autocompleter->transformObjectsToItem($value));

        if ($this->isMultiple) {
            return $result;
        }

        return $result[0] ?? null;
    }

    public function reverseTransform($id)
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
