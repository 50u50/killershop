<?php

namespace App\Api\Interfaces;

interface DiscounterInterface
{
    public function setDiscountStrategies(array $discountStrategies);

    public function setDiscountPrice(DiscountableInterface &$discountable);
}
