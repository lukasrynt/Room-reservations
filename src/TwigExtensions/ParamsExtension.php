<?php
/**
 * @author Lukas Rynt
 */

namespace App\TwigExtensions;

use App\Services\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ParamsExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('nextPageParams', array($this, 'nextPageParams')),
            new TwigFunction('prevPageParams', array($this, 'prevPageParams')),
            new TwigFunction('currentPage', array($this, 'currentPage')),
            new TwigFunction('pagesCount', array($this, 'pagesCount'))
        ];
    }

    public function nextPageParams(Request $request, int $entitiesCount): array
    {
        return Paginator::updateQueryParams($request->query->all(), true, $entitiesCount);
    }

    public function prevPageParams(Request $request): array
    {
        return Paginator::updateQueryParams($request->query->all(), false);
    }

    public function currentPage(Request $request): int
    {
        return Paginator::getCurrentPageFromParams($request->query->all());
    }

    public function pagesCount(Request $request, int $entitiesCount): int
    {
        return Paginator::getPagesCount($request->query->all(), $entitiesCount);
    }
}