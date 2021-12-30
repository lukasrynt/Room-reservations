<?php
/**
 * @author Lukas Rynt
 */

namespace App\Services;

use Doctrine\Common\Collections\Criteria;
use PhpParser\Node\Param;

class Paginator
{
    const DEFAULT_PAGE_SIZE = 5;
    const URL_KEY = 'paginate';

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

    public static function getCurrentPageFromParams(array $params): int
    {
        $page = 1;
        if (array_key_exists(self::URL_KEY, $params)) {
            $page = $params[self::URL_KEY]['page'] ?? 1;
        }
        return $page;
    }

    public static function updateQueryParams(array $params, bool $next, int $limit = 0): array
    {
        if (array_key_exists(self::URL_KEY, $params)) {
            $params[self::URL_KEY] = self::updateParams($params[self::URL_KEY], $next, $limit);
        } else {
            $params[self::URL_KEY] = ['page' => self::getPage(null, $next, $limit)];
        }
        return self::filterOutParams($params);
    }

    private static function filterOutParams(array $queries): array
    {
        $res = [];
        foreach ($queries as $key => $val) {
            if ($key == 'filter_by' || $key == self::URL_KEY || $key == 'order_by') {
                $res[$key] = $val;
            }
        }
        return $res;
    }

    public static function getPagesCount(array $params, int $entitiesCount): int
    {
        $pageSize = self::DEFAULT_PAGE_SIZE;
        if (array_key_exists(self::URL_KEY, $params)) {
            $pageSize = $params[self::URL_KEY]['page_size'] ?? self::DEFAULT_PAGE_SIZE;
        }
        if ($pageSize <= 0) {
            $pageSize = self::DEFAULT_PAGE_SIZE;
        }
        return ceil($entitiesCount / floatval($pageSize));
    }

    private static function updateParams(array $paginatorParams, bool $next, int $limit = 0): array
    {
        $paginatorParams['page'] = self::getPage($paginatorParams['page'] ?? null, $next, $limit);
        return $paginatorParams;
    }

    private static function getPage(?int $currentPage, bool $next, int $limit = 0): int
    {
        $currentPage ??= 1;
        if ($next) {
            return $currentPage >= $limit ? $limit : $currentPage + 1;
        }
        else {
            return $currentPage <= 1 ? 1 : $currentPage - 1;
        }
    }
}