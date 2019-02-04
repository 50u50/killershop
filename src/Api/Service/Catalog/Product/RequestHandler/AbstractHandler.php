<?php

namespace App\Api\Service\Catalog\Product\RequestHandler;

use App\Api\Interfaces\ProductRequestDataHandlerInterface;
use App\Api\Entity\Catalog\Product;

abstract class AbstractHandler
{
    /**
     * @var ProductRequestDataHandlerInterface
     */
    protected $next;

    public function next(Product &$product, array $data): Product
    {
        return $this->next ? $this->next->handle($product, $data) : $product;
    }
}
