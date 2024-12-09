<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Exception;

use BadMethodCallException;

class NotImplementedMethodException extends BadMethodCallException
{
    public function __construct(string $method)
    {
        parent::__construct(sprintf('Need implement logic for method "%s', $method));
    }
}
