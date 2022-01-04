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
    public static function convertToUrlParams(array $params): array
    {
        $res = [];
        foreach ($params as $key => $value) {
            $res[$key] = self::mapArrayToParams($value);
        }
        return $res;
    }

    public static function getParamsFromUrl(array $queryParams, array $searchParams = null, bool $autoParams = true): array
    {
        $res = [];
        $paginateParams = self::getFilters($queryParams, Paginator::URL_KEY);
        if ($autoParams && !array_key_exists('page_size', $paginateParams)) {
            $paginateParams['page_size'] = Paginator::DEFAULT_PAGE_SIZE;
        }
        $res[Paginator::URL_KEY] = $paginateParams;

        $orderParams = self::getFilters($queryParams, Orderer::URL_KEY);
        if ($autoParams && empty($orderParams)) {
            $orderParams['id'] = 'ASC';
        }
        $res[Orderer::URL_KEY] = $orderParams;

        if ($searchParams) {
            $filters = array_filter($searchParams, fn($val) => $val !== null);
        } else {
            $filters = array_filter(self::getFilters($queryParams, Filter::URL_KEY), fn($val) => $val !== null);
        }
        $res[Filter::URL_KEY] = $filters;
        return $res;
    }

    /**
     * Parse params in format ?order_by=first_order:ASC,second_order:DESC or for any other
     * @param array $params
     * @param string $type
     * @return array|null
     */
    public static function getFilters(array $params, string $type): ?array
    {
        if (!array_key_exists($type, $params)) {
            return [];
        }
        $filters = explode(',', $params[$type]);
        $mapped = [];
        foreach ($filters as $filter) {
            $expl = explode(':', $filter);
            if (count($expl) == 2) {
                $mapped[$expl[0]] = $expl[1];
            }
        }
        return $mapped;
    }

    public static function mapArrayToParams(array $array): ?string
    {
        $res = '';
        $keys = array_keys($array);
        $last = end($keys);
        foreach ($array as $key => $val) {
            if ($val) {
                $res .= $key . ':' . $val;
                if ($key != $last) {
                    $res .= ',';
                }
            }
        }
        return $res;
    }
}