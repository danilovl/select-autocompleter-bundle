<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat;

class Pagination
{
    /**
     * @var bool
     */
    public $more = false;

    /**
     * @param array $parameters
     * @return static
     */
    public static function fromConfig(array $parameters): self
    {
        $item = new self;
        $item->more = $parameters['more'] ?? false;

        return $item;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'more' => $this->more
        ];
    }
}
