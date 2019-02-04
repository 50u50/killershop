<?php

namespace App\Api\Interfaces;

use App\Api\Entity\Catalog\Product;

interface ProductRequestDataHandlerInterface
{
    public function handle(Product &$product, array $data):Product;

    public function next(Product &$product, array $data):Product;
}
