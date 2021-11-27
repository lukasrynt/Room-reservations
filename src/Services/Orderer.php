<?php
/**
 * @author Lukas Rynt
 */

namespace App\Services;

use Doctrine\Common\Collections\Criteria;

/**
 * Class for ordering elements from order filters in the following format:
 *      ['name' => 'ASC']
 *      ['name' => 'DESC']
 *      ['name' => Criteria::ASC]
 */
class Orderer
{
    private Criteria $criteria;

    /**
     * @param Criteria|null $criteria
     */
    public function __construct(Criteria $criteria = null)
    {
        $this->criteria = $criteria ?? Criteria::create();
    }

    public function getOrderCriteria(?array $orders): Criteria
    {
        if (!$orders)
            return $this->criteria;
        $this->criteria->orderBy($orders);
        return $this->criteria;
    }
}