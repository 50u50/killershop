<?php

namespace App\Api\Service\Catalog\Product\Price\Discount\Strategy;

use App\Api\Interfaces\DiscountableInterface;
use App\Api\Interfaces\DiscountStrategyInterface;

class PercentageStrategy implements DiscountStrategyInterface
{
    public function getDiscount(DiscountableInterface $discountable): float
    {
        if ($discountable->getDiscountRule() !== DiscountableInterface::DISCOUNT_RULE_PERCENTAGE) {
            return 0;
        }

        return (float)$discountable->getBasePrice() /
            100 * $discountable->getDiscountValue();
    }
}
