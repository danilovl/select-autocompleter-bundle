<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Twig;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class RenderBlockControlTwigExtension extends AbstractExtension
{
    private bool $isRenderBlockStyle = false;

    public function getFunctions(): array
    {
        return [
            new TwigFunction('select_autocompleter_is_render_block_style', $this->isRenderBlockStyle(...)),
        ];
    }

    public function isRenderBlockStyle(): bool
    {
        if ($this->isRenderBlockStyle) {
            return true;
        }

        $this->isRenderBlockStyle = true;

        return false;
    }
}
