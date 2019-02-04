<?php

namespace App\Api\Service;

class MoneyService
{
    const PRECISION = 2;
    const VALUE_UNDEFINED = '-';

    /**
     * @todo store in DB table and set per store/customer
     */
    const DEFAULT_CURRENCY_CODE = 'eur';

    public function round(float $val): float
    {
        return round($val, self::PRECISION);
    }

    public function getPriceString($val): string
    {
        if (is_string($val)) {
            if (!strlen($val)) {
                return self::VALUE_UNDEFINED;
            }
            $val = (float)$val;
        }
        return sprintf("%0.2f", $val);
    }
}
