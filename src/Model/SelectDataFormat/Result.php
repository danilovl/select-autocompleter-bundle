<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat;

class Result
{
    /**
     * @var Item[]
     */
    public array $results = [];
    public ?Pagination $pagination = null;

    public static function fromConfig(array $parameters): self
    {
        $item = new self;
        $item->results = $parameters['items'] ?? [];
        $item->pagination = $parameters['pagination'] ?? null;

        return $item;
    }

    public function toArray(): array
    {
        return [
            'results' => $this->results,
            'pagination' => $this->pagination->toArray()
        ];
    }
}
