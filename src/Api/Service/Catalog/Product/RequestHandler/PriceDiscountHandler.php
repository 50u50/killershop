<?php

namespace App\Api\Service\Catalog\Product\RequestHandler;

use App\Api\Entity\Catalog\Product;
use App\Api\Exception\BadRequestException;
use App\Api\Factory\GenericFactory;
use App\Api\Form\Catalog\Product\Price\DiscountType;
use App\Api\Interfaces\ProductRequestDataHandlerInterface;
use Symfony\Component\Form\FormFactory;

class PriceDiscountHandler extends AbstractHandler implements ProductRequestDataHandlerInterface
{
    const REQUEST_DISCOUNT = 'price_discount';
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
        BundleProductHandler $handler
    )
    {
        $this->genericFactory = $genericFactory;
        $this->formFactory = $formFactory;
        $this->next = $handler;
    }

    public function handle(Product &$product, array $data): Product
    {
        if (empty($data[self::REQUEST_DISCOUNT])) {
            return $product;
        }
        /** @var Product\Price\Discount $discount */
        $discount = $this->genericFactory->create(Product\Price\Discount::class);
        $discountForm = $this->formFactory->create(DiscountType::class, $discount);
        $discountForm->submit($data[self::REQUEST_DISCOUNT]);
        if (!$discountForm->isValid()) {
            throw new BadRequestException($discountForm->getErrors(true, false));
        }
        $price = $product->getPrice();
        $discount->setPrice($price);
        $price->setDiscount($discount);

        return $this->next($product, $data);
    }
}
