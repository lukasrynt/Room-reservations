<?php
/**
 * @author Lukas Rynt
 */

namespace App\Controller\Helpers;

/**
 * Parses query params, e.g.:
 *      /users/?order_by=lastName:DESC&filter_by=phoneNumber:777888999&paginate=page_size:2,page:1
 */
class ParamsParser
{
    private array $params;

    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Parse params in format ?order_by=first_order:ASC,second_order:DESC or for any other
     */
    public function getFilters(string $type): ?array
    {
        if (!array_key_exists($type, $this->params))
            return null;
        $filters = explode(',', $this->params[$type]);
        $mapped = [];
        foreach ($filters as $filter) {
            $expl = explode(':', $filter);
            $mapped[$expl[0]] = $expl[1];
        }

        return $mapped;
    }
}