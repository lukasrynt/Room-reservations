<?php
/**
 * @author Lukas Rynt
 */

namespace App\Services;

use Doctrine\Common\Collections\Criteria;

/**
 * Class for creating criteria from filters in the following format:
 *      ['name' => 'value']
 * Where LIKE type generates a contains query and EXACT generates exact matches.
 * Default is EXACT type.
 */
class Filter
{
    private Criteria $criteria;
    private bool $andMode;

    /**
     * @param Criteria|null $criteria
     * @param bool $andMode
     */
    public function __construct(Criteria $criteria = null, bool $andMode = true)
    {
        $this->criteria = $criteria ?? Criteria::create();
        $this->andMode = $andMode;
    }

    public function getFilterCriteria(?array $attributes): Criteria
    {
        if (!$attributes)
            return $this->criteria;
        foreach ($attributes as $key => $value) {
            if (is_numeric($value))
                $expression = Criteria::expr()->eq($key, $value);
            else
                $expression = Criteria::expr()->contains($key, $value);
            if ($this->andMode)
                $this->criteria->andWhere($expression);
            else
                $this->criteria->orWhere($expression);
        }
        return $this->criteria;
    }
}