<?php
/**
 * @author Lukas Rynt
 */

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Exception\InvalidParameterException;

/**
 * Class for creating queries from filters in the following format:
 *      ['name' => 'value:EXACT']
 *      ['name' => 'value:LIKE']
 *      ['name' => 'value']
 * Where LIKE type generates a like query to db and EXACT generates exact queries.
 * Default is EXACT type.
 */
class FilterQueryBuilder
{
    private QueryBuilder $builder;
    private bool $andMode;

    /**
     * @param ServiceEntityRepository $entityRepository
     * @param bool $andMode
     */
    public function __construct(ServiceEntityRepository $entityRepository, bool $andMode = true)
    {
        $this->builder = $entityRepository->createQueryBuilder('e');
        $this->andMode =  $andMode;
    }

    public function createQuery(array $attributes): Query
    {
        foreach ($attributes as $key => $val) {
            $this->parseAttribute($val, $name, $type);
            if ($val['type'] === 'LIKE') {
                $this->addCondition("e.$key LIKE :$key");
                $this->builder->setParameter($key, '%' . $val['name'] . '%');
            }
            else if ($val['type'] === 'EXACT')
                $this->addCondition("e.$key = :$key");
                $this->builder->setParameter($key, $val['name']);
        }
        return $this->builder->getQuery();
    }

    private function addCondition(string $query): void
    {
        if ($this->andMode)
            $this->builder->andWhere($query);
        else
            $this->builder->orWhere($query);
    }

    private function parseAttribute(string $attribute, string &$name, &$type): void
    {
        $split = explode(':', $attribute);
        if (count($split) == 1) {
            $name = $split[0];
            $type = 'EXACT';
        }
        else if (count($split) == 2) {
            $name = $split[0];
            $type = $split[1];
        }
        else
            throw new InvalidParameterException();
    }
}