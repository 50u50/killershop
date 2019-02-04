<?php

namespace App\Api\Interfaces;

interface DiscountStrategyInterface
{
    /**
     * @param DiscountableInterface $object
     * @return float
     */
    public function getDiscount(DiscountableInterface $object): float;
}
