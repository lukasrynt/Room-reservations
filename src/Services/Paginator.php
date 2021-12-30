<?php
/**
 * @author Lukas Rynt
 */

namespace App\Services;

use Doctrine\Common\Collections\Criteria;
use PhpParser\Node\Param;

class Paginator
{
    const DEFAULT_PAGE_SIZE = 20;

    private Criteria $criteria;

    /**
     * @param Criteria|null $criteria
     */
    public function __construct(Criteria $criteria = null)
    {
        $this->criteria = $criteria ?? Criteria::create();
    }

    public function getCriteriaForPage(?array $attributes): Criteria
    {
        $pageSize = $attributes['page_size'] ?? self::DEFAULT_PAGE_SIZE;
        $page = (isset($attributes['page']) && $attributes['page'] >= 1) ? $attributes['page'] : 1;
        $this->criteria->setMaxResults($pageSize);
        $this->criteria->setFirstResult(($page - 1) * $pageSize);
        return $this->criteria;
    }

    public static function getCurrentPageFromParams(array $queries): int
    {
        $attributes = ParamsParser::getFilters($queries, 'paginate');
        return (isset($attributes['page']) && $attributes['page'] >= 1) ? $attributes['page'] : 1;
    }

    public static function updateQueryParams(?array $queries, bool $next, ?array $searchParams, int $limit = 0): array
    {
        if ($queries) {
            $paginateParams = ParamsParser::getFilters($queries, 'paginate');
            $paginateParams = self::updateParams($paginateParams, $next, $limit);
            $queries['paginate'] = ParamsParser::mapArrayToParams($paginateParams);
        } else {
            $queries = ['paginate' => 'page:' . self::getPage(null, $next, $limit)];
        }
        if ($searchParams) {
            $queries['filter_by'] = ParamsParser::mapArrayToParams($searchParams);
        }
        $queries = self::filterOutParams($queries);
        dump($queries);
        return $queries;
    }

    private static function filterOutParams(array $queries): array
    {
        $res = [];
        foreach ($queries as $key => $val) {
            if ($key == 'filter_by' || $key == 'paginate' || $key == 'order_by') {
                $res[$key] = $val;
            }
        }
        return $res;
    }

    public static function getPagesCount(?array $queries, int $entitiesCount): int
    {
        $pageSize = self::DEFAULT_PAGE_SIZE;
        if ($queries) {
            $pageSize = ParamsParser::getFilters($queries, 'paginate')['page_size'] ?? self::DEFAULT_PAGE_SIZE;
        }
        if ($pageSize <= 0) {
            $pageSize = self::DEFAULT_PAGE_SIZE;
        }
        return $entitiesCount / $pageSize;
    }

    private static function updateParams(array $paginatorParams, bool $next, int $limit = 0): array
    {
        $paginatorParams['page'] = self::getPage($paginatorParams['page'] ?? null, $next, $limit);
        return $paginatorParams;
    }

    private static function getPage(?int $currentPage, bool $next, int $limit = 0): int
    {
        $currentPage ??= 1;
        if ($next)
            return $currentPage >= $limit ? $limit : $currentPage + 1;
        else
            return $currentPage <= 1 ? 1 : $currentPage - 1;
    }
}