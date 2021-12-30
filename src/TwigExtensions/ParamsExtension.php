<?php
/**
 * @author Lukas Rynt
 */

namespace App\TwigExtensions;

use App\Services\Paginator;
use App\Services\ParamsParser;
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

    public function nextPageParams(array $params, int $entitiesCount): array
    {
        return ParamsParser::convertToUrlParams(
            Paginator::updateQueryParams($params, true, $entitiesCount)
        );
    }

    public function prevPageParams(array $params): array
    {
        return ParamsParser::convertToUrlParams(
            Paginator::updateQueryParams($params, false)
        );
    }

    public function currentPage(array $params): int
    {
        return Paginator::getCurrentPageFromParams($params);
    }

    public function pagesCount(array $params, int $entitiesCount): int
    {
        return Paginator::getPagesCount($params, $entitiesCount);
    }
}