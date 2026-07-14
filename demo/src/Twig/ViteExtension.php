<?php

namespace App\Twig;

use App\Service\ViteAssetMapper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ViteExtension extends AbstractExtension
{
    public function __construct(
        private ViteAssetMapper $vite,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('vite_dev', [$this->vite, 'isDevServer']),
            new TwigFunction('vite_js', [$this->vite, 'js']),
            new TwigFunction('vite_css', [$this->vite, 'css']),
        ];
    }
}
