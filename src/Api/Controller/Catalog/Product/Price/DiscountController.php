<?php

namespace App\Api\Controller\Catalog\Product\Price;

use App\Api\Entity\Catalog\Product\Price\Discount;
use App\Api\Exception\BadRequestException;
use App\Api\Form\Catalog\Product\Price\DiscountType;
use App\Api\Service\Catalog\Product\Price\DiscountService;
use App\Api\Service\Catalog\ProductService;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api/catalog/product/price/discount")
 */
class DiscountController extends AbstractController
{
    /**
     * @var ProductService
     */
    private $productService;
    /**
     * @var DiscountService
     */
    private $discountService;

    public function __construct(
        DiscountService $discountService,
        ProductService $productService
    )
    {
        $this->productService = $productService;
        $this->discountService = $discountService;
    }

    /**
     * Sets discount to the product (list of products)
     * @todo should take currency in consideration when fetching product price list
     * @todo Refactor it, see Price controller/new
     *
     * @Route("/new", name="catalog_product_price_discount_new", methods={"POST"})
     *
     * @SWG\Tag(name="price_discount")
     * @SWG\Post(summary="Post to URL",
     *     @SWG\Parameter(name="body",in="body",required=true,
     *          @SWG\Schema(
     *              @SWG\Property(property="price_discount",type="object",
     *                      @SWG\Property(property="rule", type="string",example="percentage",maximum=16),
     *                      @SWG\Property(property="value", type="decimal",example="25"),
     *                      @SWG\Property(property="product_code", type="string",example="10089879870879,10089879870880"),
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(response=200,description="Creates a new price discount for the product"),
     * )
     */
    public function new(Request $request): Response
    {
        $requestData = $request->request->all();
        if (empty($requestData['price_discount']['product_code']) ||
            !$codes = explode(',', $requestData['price_discount']['product_code'])) {
            throw new BadRequestException('Invalid product_code value');
        }
        $products = $this->productService->findByCodes($codes);
        if (empty($products)) {
            throw new BadRequestException('Requested products not found');
        }
        /**
         * @todo exception on some items missing
         */
        foreach ($products as $product) {
            if (!$product->getPrice()) {
                continue;
            }
            $discount = new Discount();
            $form = $this->createForm(DiscountType::class, $discount);
            $form->submit($requestData['price_discount']);
            $discount->setPrice($product->getPrice());

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                /**
                 * @todo delete existing price discounts?
                 */
                $entityManager->persist($discount);
                $entityManager->flush();
            }
        }

        $routeOptions = [
            /** Redirect to the last added discount */
            'id' => $discount->getId(),
            '_format' => $request->get('_format'),
        ];

        return new RedirectResponse($this->generateUrl('catalog_product_price_discount_show', $routeOptions));

    }

    /**
     * @Route("/{id}", name="catalog_product_price_discount_show", methods={"GET"})
     *
     * @SWG\Tag(name="price_discount")
     * @SWG\Get(summary="Get to URL",
     *     @SWG\Response(response=200,description="Displays product price discount"),
     * )
     */
    public function show(Discount $discount): Response
    {
        return new JsonResponse($this->discountService->extract($discount));
    }

    /**
     * @Route("/{id}/edit", name="catalog_product_price_discount_edit", methods={"POST"})
     *
     * @SWG\Tag(name="price_discount")
     * @SWG\Post(summary="Post to URL",
     *     @SWG\Parameter(name="body",in="body",required=true,
     *          @SWG\Schema(
     *              @SWG\Property(property="price_discount",type="object",
     *                 @SWG\Property(property="rule", type="string",example="percentage",maximum=16),
     *                 @SWG\Property(property="value", type="decimal",example="25"),
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(response=200,description="Edits product price discount"),
     * )
     * @todo refactor it, use service
     */
    public function edit(Request $request, Discount $discount): Response
    {
        $data = $request->request->all();
        if (empty($data['price_discount'])) {
            throw new BadRequestException('price_discount can not be empty');
        }
        $form = $this->createForm(DiscountType::class, $discount);
        $form->submit($data['price_discount'], false);
        if (!$form->isValid()) {
            throw new BadRequestException($form->getErrors(true, false));
        }
        $this->getDoctrine()->getManager()->flush();

        $routeOptions = [
            'id' => $discount->getId(),
            '_format' => $request->get('_format'),
        ];

        return new RedirectResponse($this->generateUrl('catalog_product_price_discount_show', $routeOptions));

    }

    /**
     * @Route("/{id}", name="catalog_product_price_discount_delete", methods={"DELETE"})
     *
     * @SWG\Tag(name="price_discount")
     * @SWG\Delete(summary="Delete to URL",
     *     @SWG\Response(response=200,description="Deletes product price discount"),
     * )
     * @todo refactor it, use service
     */
    public function delete(Request $request, Discount $discount): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($discount);
        $entityManager->flush();

        return new JsonResponse();
    }
}
