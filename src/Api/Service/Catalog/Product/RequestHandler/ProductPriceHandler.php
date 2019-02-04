<?php

namespace App\Api\Service\Catalog\Product\RequestHandler;

use App\Api\Entity\Catalog\Product;
use App\Api\Exception\BadRequestException;
use App\Api\Factory\GenericFactory;
use App\Api\Form\Catalog\Product\PriceType;
use App\Api\Interfaces\ProductRequestDataHandlerInterface;
use Symfony\Component\Form\FormFactory;

class ProductPriceHandler extends AbstractHandler implements ProductRequestDataHandlerInterface
{
    const REQUEST_PRICE = 'product_price';
    /**
     * @var GenericFactory
     */
    private $genericFactory;
    /**
     * @var FormFactory
     */
    private $formFactory;

    public function __construct(
        GenericFactory $genericFactory,
        FormFactory $formFactory,
        PriceDiscountHandler $priceDiscountHandler
    )
    {
        $this->genericFactory = $genericFactory;
        $this->formFactory = $formFactory;
        $this->next = $priceDiscountHandler;
    }

    public function handle(Product &$product, array $data):Product
    {
        if (empty($data[self::REQUEST_PRICE])) {
            return $product;
        }
        /** @var Product\Price $price */
        $price = $product->getPrice()?? $this->genericFactory->create(Product\Price::class);

        $priceForm = $this->formFactory->create(PriceType::class, $price);

        $priceForm->submit($data[self::REQUEST_PRICE]);
        if (!$priceForm->isValid()) {
            throw new BadRequestException($priceForm->getErrors(true, false));
        }
        $price->setProduct($product);
        $product->setPrice($price);

        return $this->next($product, $data);
    }
}
