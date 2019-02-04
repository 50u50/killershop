<?php

namespace App\Api\Service\Catalog\Product\Price;

use App\Api\Entity\Catalog\Product\Price\Discount;

class DiscountHydrator
{
    public function extract(Discount $discount): array
    {
        return [
            'id' => $discount->getId(),
            'price_id' => $discount->getPrice()->getId(),
            'rule' => $discount->getRule(),
            'value' => $discount->getValue(),
        ];
    }
}
