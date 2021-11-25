<?php
/**
 * @author Lukas Rynt
 */

namespace App\TwigExtensions;

use App\Controller\Helpers\ParamsParser;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ParamsExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('nextPageParams', array($this, 'nextPageParams')),
            new TwigFunction('prevPageParams', array($this, 'prevPageParams'))
        ];
    }

    public function nextPageParams($queries): array
    {
        return $this->pageParams($queries, +1, 1);
    }

    public function prevPageParams($queries): array
    {
        return $this->pageParams($queries, -1, 0);

    }

    private function pageParams($queries, $offset, $origPage): array
    {
        if ($queries) {
            $paginateQueries = (new ParamsParser($queries))->getFilters('paginate');
            if (array_key_exists('page', $paginateQueries))
                $paginateQueries['page'] += $offset;
            $queries['paginate'] = ParamsParser::mapArrayToParams($paginateQueries);
        } else
            $queries['paginate'] = 'page:' . $origPage;
        return $queries;
    }
}