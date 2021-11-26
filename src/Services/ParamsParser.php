<?php
/**
 * @author Lukas Rynt
 */

namespace App\Services;

/**
 * Parses query params, e.g.:
 *      /users/?order_by=lastName:DESC&filter_by=phoneNumber:777888999&paginate=page_size:2,page:1
 */
class ParamsParser
{
    /**
     * Parse params in format ?order_by=first_order:ASC,second_order:DESC or for any other
     * @param array $params
     * @param string $type
     * @return array|null
     */
    public static function getFilters(array $params, string $type): ?array
    {
        if (!array_key_exists($type, $params))
            return null;
        $filters = explode(',', $params[$type]);
        $mapped = [];
        foreach ($filters as $filter) {
            $expl = explode(':', $filter);
            if (count($expl) == 2)
                $mapped[$expl[0]] = $expl[1];
        }
        return $mapped;
    }

    public static function mapArrayToParams(array $array): ?string
    {
        $res = '';
        $keys = array_keys($array);
        $last = end($keys);
        foreach ($array as $key => $val) {
            $res .= $key . ':' . $val;
            if ($key != $last)
                $res .= ',';
        }
        return $res;
    }
}