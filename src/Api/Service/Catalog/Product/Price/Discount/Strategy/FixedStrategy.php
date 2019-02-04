<?php

namespace App\Api\Service\Catalog\Product\Price\Discount\Strategy;

use App\Api\Interfaces\DiscountableInterface;
use App\Api\Interfaces\DiscountStrategyInterface;

class FixedStrategy implements DiscountStrategyInterface
{
    public function getDiscount(DiscountableInterface $discountable): float
    {
        if ($discountable->getDiscountRule() !== DiscountableInterface::DISCOUNT_RULE_FIXED) {
            return 0;
        }

        return (float)$discountable->getDiscountValue();
    }
}
