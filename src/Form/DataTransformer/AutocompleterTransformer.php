<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Form\DataTransformer;

use Danilovl\SelectAutocompleterBundle\Helper\ArrayHelper;
use Danilovl\SelectAutocompleterBundle\Interfaces\AutocompleterInterface;
use Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat\Item;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\PropertyAccess\PropertyAccess;

readonly class AutocompleterTransformer implements DataTransformerInterface
{
    public function __construct(
        private AutocompleterInterface $autocompleter,
        private bool $isMultiple = false
    ) {}

    public function transform(mixed $value): mixed
    {
        if (empty($value)) {
            return null;
        }

        if ($this->isMultiple && !is_iterable($value)) {
            throw new TransformationFailedException;
        }

        /** @var object[] $value */
        $value = !$this->isMultiple ? [$value] : $value;

        $items = $this->autocompleter->transformObjectsToItem($value);
        $result = array_map(fn (Item $item): array => $this->transformItemToArray($item), $items);

        if ($this->isMultiple) {
            return $result;
        }

        return $result[0] ?? null;
    }

    public function reverseTransform(mixed $value): mixed
    {
        if (empty($value)) {
            return $this->isMultiple ? [] : null;
        }

        if ($this->isMultiple && !is_iterable($value)) {
            throw new TransformationFailedException;
        }

        if (!$this->isMultiple) {
            $value = [$value];
        }

        /** @var string[] $value */
        $values = array_map('intval', $value);
        $result = $this->autocompleter->reverseTransform($values);

        $resultIds = [];
        foreach ($result as $item) {
            $resultIds[] = (PropertyAccess::createPropertyAccessor())->getValue($item, $this->autocompleter->getConfig()->idProperty);
        }

        $diff = array_diff($value, $resultIds);
        if (count($diff) > 0) {
            $privateErrorMessage = sprintf('Invalid identifiers "%s".', implode(', ', $diff));

            $failure = new TransformationFailedException($privateErrorMessage);
            $failure->setInvalidMessage('One or more of the given values is invalid.');

            throw $failure;
        }

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
