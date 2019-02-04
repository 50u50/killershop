<?php

namespace App\Api\Service\Catalog\Product;

use App\Api\Entity\Catalog\Product;
use App\Api\Service\Catalog\Product\RequestHandler\ProductPriceHandler;

class PriceHydrator
{
    /**
     * @var ProductPriceHandler
     */
    private $productPriceHandler;

    public function __construct(
        ProductPriceHandler $productPriceHandler
    )
    {
        $this->productPriceHandler = $productPriceHandler;
    }

    public function hydrate(Product &$product, array $data): Product
    {
        return $this->productPriceHandler->handle($product, $data);
    }

    /**
     * @todo use serializer
     * @param Product\Price $price
     * @return array
     */
    public function extract(Product\Price $price):array
    {
        return [
            'id' => $price->getId(),
            'product_id' => $price->getProduct()->getId(),
            'currency' => $price->getCurrency(),
            'base_price' => $price->getBasePrice(),
        ];
    }
}
