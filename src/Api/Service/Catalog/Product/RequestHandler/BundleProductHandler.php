<?php

namespace App\Api\Service\Catalog\Product\RequestHandler;

use App\Api\Entity\Catalog\Product;
use App\Api\Entity\Catalog\Product\Relation;
use App\Api\Exception\BadRequestException;
use App\Api\Factory\GenericFactory;
use App\Api\Interfaces\ProductRequestDataHandlerInterface;
use App\Api\Repository\Catalog\ProductRepository;

class  BundleProductHandler extends AbstractHandler implements ProductRequestDataHandlerInterface
{
    const REQUEST_BUNDLED_PRODUCTS = 'bundled_products';
    /**
     * @var GenericFactory
     */
    private $genericFactory;
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(
        ProductRepository $productRepository,
        GenericFactory $genericFactory
    )
    {
        $this->genericFactory = $genericFactory;
        $this->productRepository = $productRepository;
        $this->next = null;
    }

    public function handle(Product &$product, array $data): Product
    {
        if (empty($data[self::REQUEST_BUNDLED_PRODUCTS])) {
            return $product;
        }
        if (!$codes = explode(',', $data[self::REQUEST_BUNDLED_PRODUCTS])) {
            throw new BadRequestException('Invalid product codes format');
        }
        $children = $this->findByCodes($codes);
        if (($cnt = count($children)) < Relation::MIN_BUNDLE_SIZE) {
            /**
             * @todo add separate messages:
             * - provided list was too short initially
             * - list of not-found product codes
             * - bundle product's GTIN provided
             * ("bundle-of-bundles" is probably meaningless (throw if confirmed))
             */
            throw new BadRequestException(
                sprintf('Minimal Bundle size: %d products, %d found', Relation::MIN_BUNDLE_SIZE, $cnt)
            );
        }
        $relations = [];
        foreach ($children as $child) {
            /** @var Relation $relation */
            $relation = $this->genericFactory->create(Relation::class);
            $relation->setParent($product);
            $relation->setProduct($child);
            $relations[] = $relation;
        }
        $product->setRelations($relations);

        return $this->next($product, $data);
    }

    private function findByCodes(array $codes)
    {
        return $this->productRepository->findBy(['code' => $codes]);
    }
}
