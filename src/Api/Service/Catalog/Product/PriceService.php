<?php

namespace App\Api\Service\Catalog\Product;

use App\Api\Entity\Catalog\Product;
use App\Api\Exception\BadRequestException;
use App\Api\Factory\GenericFactory;
use App\Api\Repository\Catalog\ProductRepository;
use App\Api\Service\DiscountService;
use Doctrine\ORM\EntityManagerInterface;

class PriceService
{
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var PriceHydrator
     */
    private $priceHydrator;

    public function __construct(
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager,
        PriceHydrator $priceHydrator
    )
    {
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
        $this->priceHydrator = $priceHydrator;
    }

    public function extract(Product\Price $price):array
    {
        return $this->priceHydrator->extract($price);
    }

    public function persistRequestData(array $request):Product\Price
    {
        /**
         *
         * bundled_products -exception?
         *
         *
         */
        if(empty($request['product_price']['product_code'])) {
            throw new BadRequestException('"product_code" can not be empty');
        }
        /**
         * @var Product $product
         */
        $product = $this->productRepository
            ->findOneBy(['code' => $request['product_price']['product_code']]);
        if(!$product) {
            throw new BadRequestException('Product not found');
        }
        if($product->getPrice()) {
            /**
             * @todo take currency in consideration
             */
            throw new BadRequestException('Can not add another price record to product');
        }
        $this->priceHydrator->hydrate($product, $request);
        $this->entityManager->persist($product->getPrice());
        $this->entityManager->flush();

        return $product->getPrice();
    }
}
