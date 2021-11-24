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
     * @param int $pageSize
     */
    public function __construct(Criteria $criteria = null, int $pageSize = 20)
    {
        $this->criteria = $criteria ?? Criteria::create();
        $this->pageSize = $pageSize;
    }

    public function getCriteriaForPage($id): Criteria
    {
        $this->criteria->setMaxResults($this->pageSize);
        $this->criteria->setFirstResult($id * $this->pageSize);
        return $this->criteria;
    }
}