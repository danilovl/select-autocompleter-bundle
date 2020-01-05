<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle;

use Danilovl\SelectAutocompleterBundle\DependencyInjection\Compiler\AutocompleterCompilerPass;
use Danilovl\SelectAutocompleterBundle\DependencyInjection\AutocompleterExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SelectAutocompleterBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new AutocompleterCompilerPass);
    }

    /**
     * @return AutocompleterExtension
     */
    public function getContainerExtension(): AutocompleterExtension
    {
        return new AutocompleterExtension;
    }
}
