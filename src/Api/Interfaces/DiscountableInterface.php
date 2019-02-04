<?php

namespace App\Api\Interfaces;

interface DiscountableInterface
{
    const DISCOUNT_RULE_FIXED = 'fixed';
    const DISCOUNT_RULE_PERCENTAGE = 'percentage';

    public function getBasePrice(): ?float;

    public function getDiscountValue(): ?float;

    public function getDiscountRule(): ?string;

    public function setDiscountPrice(float $discountPrice);
}
