<?php

namespace App\Api\Service\Catalog\Product\RequestHandler;

use App\Api\Entity\Catalog\Product;
use App\Api\Exception\BadRequestException;
use App\Api\Form\Catalog\ProductType;
use App\Api\Interfaces\ProductRequestDataHandlerInterface;
use Symfony\Component\Form\FormFactory;

class SimpleProductHandlerProduct extends AbstractHandler implements ProductRequestDataHandlerInterface
{
    const REQUEST_PRODUCT = 'product';
    /**
     * @var FormFactory
     */
    private $formFactory;

    public function __construct(
        FormFactory $formFactory,
        ProductPriceHandler $handler
    )
    {
        $this->formFactory = $formFactory;
        $this->next = $handler;
    }


    public function handle(Product &$product, array $data):Product
    {
        if (empty($data[self::REQUEST_PRODUCT])) {
            return $product;
        }
        $form = $this->formFactory->create(ProductType::class, $product);
        $form->submit($data[self::REQUEST_PRODUCT]);
        if (!$form->isValid()) {
            throw new BadRequestException($form->getErrors(true, false));
        }

        return $this->next($product, $data);
    }
}
