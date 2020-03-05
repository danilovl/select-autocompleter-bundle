<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat;

class Pagination
{
    public bool $more = false;

    public static function fromConfig(array $parameters): self
    {
        $item = new self;
        $item->more = $parameters['more'] ?? false;

        return $item;
    }

    public function toArray(): array
    {
        return [
            'more' => $this->more
        ];
    }
}
