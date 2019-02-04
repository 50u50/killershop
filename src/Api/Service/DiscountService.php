<?php

namespace App\Api\Service;

use App\Api\Interfaces\DiscountableInterface;
use App\Api\Interfaces\DiscounterInterface;
use App\Api\Interfaces\DiscountStrategyInterface;

class DiscountService implements DiscounterInterface
{
    /**
     * @var DiscountStrategyInterface[]
     */
    private $discountStrategies;

    /**
     * @var MoneyService
     */
    private $moneyService;

    public function __construct(
        MoneyService $moneyService
    )
    {
        $this->moneyService = $moneyService;
    }

    public function setDiscountStrategies(array $discountStrategies)
    {
        $this->discountStrategies = $discountStrategies;
    }

    public function setDiscountPrice(DiscountableInterface &$discountable)
    {
        $discount = 0;
        if (!$basePrice = $discountable->getBasePrice()) {
            /**
             * Nothing to do the price is empty(zero)
             */
            return;
        }
        foreach ($this->discountStrategies as $strategy) {
            $discount += $strategy->getDiscount($discountable);
        }
        $discountPrice = $basePrice - $this->moneyService->round($discount);

        $discountable->setDiscountPrice($discountPrice);
    }
}
