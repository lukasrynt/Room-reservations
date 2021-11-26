<?php
/**
 * @author Lukas Rynt
 */

namespace App\Services;

use Doctrine\Common\Collections\Criteria;

class Paginator
{
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
        $pageSize = $attributes['page_size'] ?? 20;
        $page = $attributes['page'] ?? 0;
        $this->criteria->setMaxResults($pageSize);
        $this->criteria->setFirstResult($page * $pageSize);
        return $this->criteria;
    }

    public static function getCurrentPageFromParams(array $queries): int
    {
        $attributes = ParamsParser::getFilters($queries, 'paginate');
        return $attributes['page'] ?? 0;
    }

    public static function updateQueryParams(?array $queries, int $offset): array
    {
        if ($queries) {
            $paginateQueries = ParamsParser::getFilters($queries, 'paginate');
            if (array_key_exists('page', $paginateQueries))
                $paginateQueries['page'] += $offset;
            $queries['paginate'] = ParamsParser::mapArrayToParams($paginateQueries);
        } else
            $queries['paginate'] = 'page:' . ($offset > 0 ? $offset : 0);
        return $queries;
    }
}