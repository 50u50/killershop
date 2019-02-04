<?php

namespace App\Api\Service\Catalog;

use App\Api\Entity\Catalog\Product;
use App\Api\Service\Catalog\Product\RequestHandler\SimpleProductHandlerProduct;
use App\Api\Service\MoneyService;

class ProductHydrator
{
    /**
     * @var MoneyService
     */
    private $moneyService;
    /**
     * @var SimpleProductHandlerProduct
     */
    private $simpleProductHandler;

    public function __construct(
        MoneyService $moneyService,
        SimpleProductHandlerProduct $simpleProductHandler
    )
    {

        $this->moneyService = $moneyService;
        $this->simpleProductHandler = $simpleProductHandler;
    }

    public function hydrate(Product &$product, array $data): Product
    {
        return $this->simpleProductHandler->handle($product, $data);
    }

    public function extractRow(Product $product)
    {
        $basePrice = $product->getPrice() ? $product->getPrice()->getBasePrice() : '';
        $discountPrice = $product->getDiscountPrice() ?? $basePrice;

        return [
            'code' => $product->getCode(),
            'name' => $product->getName(),
            'brand' => $product->getBrand(),
            'base_price' => $this->moneyService->getPriceString($basePrice),
            'discount_price' => $this->moneyService->getPriceString($discountPrice),
        ];
    }
}
