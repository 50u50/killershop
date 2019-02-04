<?php

namespace App\Api\Service\Catalog\Product\Price;

use App\Api\Entity\Catalog\Product\Price;

class DiscountService
{
    /**
     * @var DiscountHydrator
     */
    private $discountHydrator;

    public function __construct(
        DiscountHydrator $discountHydrator
    )
    {
        $this->discountHydrator = $discountHydrator;
    }

    public function extract(Price\Discount $discount): array
    {
        return $this->discountHydrator->extract($discount);
    }
}
