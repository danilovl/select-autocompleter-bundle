<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat;

class Result
{
    /**
     * @var Item[]
     */
    public $items;

    /**
     * @var Pagination
     */
    public $pagination;

    /**
     * @param array $parameters
     * @return static
     */
    public static function fromConfig(array $parameters): self
    {
        $item = new self;
        $item->items = $parameters['items'] ?? null;
        $item->pagination = $parameters['pagination'] ?? null;

        return $item;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'results' => $this->items,
            'pagination' => $this->pagination->toArray()
        ];
    }
}
