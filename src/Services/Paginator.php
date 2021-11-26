<?php
/**
 * @author Lukas Rynt
 */

namespace App\Services;

use Doctrine\Common\Collections\Criteria;

class Paginator
{
    private int $pageSize;
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
        $pageSize = $attributes['$pageSize'] ?? 20;
        $page = $attributes['page'] ?? 0;
        $this->criteria->setMaxResults($pageSize);
        $this->criteria->setFirstResult($page * $pageSize);
        return $this->criteria;
    }
}