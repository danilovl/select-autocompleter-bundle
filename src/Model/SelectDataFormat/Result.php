<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat;

readonly class Result
{
    /**
     * @param Item[] $results
     * @param Pagination|null $pagination
     */
    private function __construct(
        public array $results,
        public ?Pagination $pagination
    ) {}

    public static function fromConfig(array $parameters): self
    {
        return new self (
            $parameters['results'] ?? [],
            $parameters['pagination'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'results' => $this->results,
            'pagination' => $this->pagination?->toArray()
        ];
    }
}
