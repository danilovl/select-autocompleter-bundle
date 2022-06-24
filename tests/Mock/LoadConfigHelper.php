<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Mock;

use Symfony\Component\Yaml\Yaml;

class LoadConfigHelper
{
    public static function localTestData(): array
    {
        return Yaml::parseFile(__DIR__ . DIRECTORY_SEPARATOR . 'test.yaml');
    }
}
