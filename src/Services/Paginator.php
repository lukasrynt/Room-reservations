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
     * @param int|null $pageSize
     */
    public function __construct(Criteria $criteria = null, int $pageSize = null)
    {
        $this->criteria = $criteria ?? Criteria::create();
        $this->pageSize = $pageSize ?? 20;
    }

    public function getCriteriaForPage(?int $id = 0): Criteria
    {
        $this->criteria->setMaxResults($this->pageSize);
        $this->criteria->setFirstResult($id * $this->pageSize);
        return $this->criteria;
    }
}