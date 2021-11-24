<?php
/**
 * @author Lukas Rynt
 */

namespace App\Services;

use Doctrine\Common\Collections\Criteria;
use FOS\RestBundle\Exception\InvalidParameterException;

/**
 * Class for creating queries from filters in the following format:
 *      ['name' => 'value:EXACT']
 *      ['name' => 'value:LIKE']
 *      ['name' => 'value']
 * Where LIKE type generates a like query to db and EXACT generates exact queries.
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

    public function createQuery(array $attributes): Criteria
    {
        foreach ($attributes as $key => $attr) {
            $this->parseAttribute($attr, $value, $type);
            if ($type === 'LIKE' && !is_numeric($value))
                $expression = Criteria::expr()->contains($key, $value);
            else
                $expression = Criteria::expr()->eq($key, $value);
            if ($this->andMode)
                $this->criteria->andWhere($expression);
            else
                $this->criteria->orWhere($expression);
        }
        return $this->criteria;
    }

    private function parseAttribute(string $attribute, ?string &$value, ?string &$type): void
    {
        $split = explode(':', $attribute);
        if (count($split) == 1) {
            $value = $split[0];
            $type = 'EXACT';
        }
        else if (count($split) == 2) {
            $value = $split[0];
            $type = $split[1];
        }
        else
            throw new InvalidParameterException();
    }
}