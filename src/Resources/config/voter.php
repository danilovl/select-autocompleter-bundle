<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Danilovl\SelectAutocompleterBundle\Security\Voter\DefaultVoter;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('danilovl.select_autocompleter.voter.default', DefaultVoter::class)
        ->args([
            service('security.helper')
        ])
        ->public()
        ->tag('security.voter')
        ->alias(DefaultVoter::class, 'danilovl.select_autocompleter.voter.default');
};
