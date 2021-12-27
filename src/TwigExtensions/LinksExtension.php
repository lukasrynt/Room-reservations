<?php


namespace App\TwigExtensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LinksExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('linkClassForUrl', array($this, 'linkClassForUrl'))
        ];
    }

    public function linkClassForUrl(string $path, string $resource): string
    {
        $split = explode('/', $path);
        $active = in_array($resource, $split);
        return $active ? 'button-main' : 'button-secondary-outline';
    }
}