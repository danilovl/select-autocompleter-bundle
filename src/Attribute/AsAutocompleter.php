<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class AsAutocompleter
{
    public function __construct(public readonly string $alias)
    {
    }
}
