<?php

namespace App\Api\Service\Catalog;

use App\Api\Entity\Catalog\Product;
use App\Api\Factory\GenericFactory;
use App\Api\Repository\Catalog\ProductRepository;
use App\Api\Service\DiscountService;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    /**
     * @var ProductHydrator
     */
    private $productHydrator;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var DiscountService
     */
    private $discountService;
    /**
     * @var GenericFactory
     */
    private $genericFactory;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        ProductRepository $productRepository,
        ProductHydrator $productHydrator,
        DiscountService $discountService,
        GenericFactory $genericFactory,
        EntityManagerInterface $entityManager
    )
    {
        $this->productHydrator = $productHydrator;
        $this->productRepository = $productRepository;
        $this->discountService = $discountService;
        $this->genericFactory = $genericFactory;
        $this->entityManager = $entityManager;
    }

    public function setDiscountStrategies($discountStrategies)
    {
        $this->discountService->setDiscountStrategies($discountStrategies);
    }

    public function extract(Product $product):array
    {
        return $this->productHydrator->extractRow($product);
    }

    public function extractAll()
    {
        $res = [];
        foreach ($this->productRepository->findAll() as $product) {
            /** Apply discounts to prices */
            $this->discountService->setDiscountPrice($product);
            $row = $this->productHydrator->extractRow($product);
            foreach ($product->getRelations() as $relation) {
                $row['bundled_products'][] = $this->productHydrator->extractRow($relation->getProduct());
            }
            $res[] = $row;
        }

        return $res;
    }

    public function findOneByCode(string $code)
    {
        return $this->productRepository->findOneBy(['code' => $code]);
    }

    /**
     * @param array $codes
     * @return \App\Api\Entity\Catalog\Product[]
     */
    public function findByCodes(array $codes)
    {
        return $this->productRepository->findBy(['code' => $codes]);
    }

    public function persistRequestData(array $request):Product
    {
        /**
         * @var Product $product
         */
        $product = $this->genericFactory->create(Product::class);
        $this->productHydrator->hydrate($product, $request);
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }
}
