<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class ItemChildren
{
    public function __construct(
        public int|string $id,
        public string $text,
        public ?string $image = null
    ) {}
}
