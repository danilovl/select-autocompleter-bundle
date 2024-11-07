<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Tests\Unit\Twig;

use Danilovl\SelectAutocompleterBundle\DependencyInjection\AutocompleterExtension;
use Danilovl\SelectAutocompleterBundle\Tests\Mock\LoadConfigHelper;
use Danilovl\SelectAutocompleterBundle\Twig\RenderBlockControlTwigExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class RenderBlockControlTwigExtensionTest extends TestCase
{
    private Environment $twig;

    protected function setUp(): void
    {
        $this->twig = new Environment(new FilesystemLoader, [
            'cache' => __DIR__ . '/../../../var/cache/twig-test'
        ]);
    }

    public function testExtension(): ContainerBuilder
    {
        $parameterBag = new ParameterBag([
            'twig.form.resources' => ['@SelectAutocompleter/Form/select_autocompleter.html.twig']
        ]);
        $container = new ContainerBuilder($parameterBag);
        (new AutocompleterExtension)->load(LoadConfigHelper::localTestData(), $container);

        $this->expectNotToPerformAssertions();

        return $container;
    }

    public function testTwigExtension(): void
    {
        $this->twig->addExtension(new RenderBlockControlTwigExtension);
        $template = '{% if select_autocompleter_is_render_block_style() %}yes{% else %}no{% endif %}';

        $output = $this->twig->createTemplate($template)->render();
        $this->assertSame('no', $output);

        $output = $this->twig->createTemplate($template)->render();
        $this->assertSame('yes', $output);

        $output = $this->twig->createTemplate($template)->render();
        $this->assertSame('yes', $output);
    }
}
