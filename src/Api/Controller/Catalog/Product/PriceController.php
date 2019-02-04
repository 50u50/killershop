<?php

namespace App\Api\Controller\Catalog\Product;

use App\Api\Entity\Catalog\Product\Price;
use App\Api\Exception\BadRequestException;
use App\Api\Form\Catalog\Product\PriceType;
use App\Api\Service\Catalog\Product\PriceService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/catalog/product/price")
 */
class PriceController extends AbstractFOSRestController
{
    /**
     * @var PriceService
     */
    private $priceService;

    public function __construct(
        PriceService $priceService
    )
    {
        $this->priceService = $priceService;
    }

    /**
     * @Route("/new", name="catalog_product_price_new", methods={"POST"})
     *
     * @SWG\Tag(name="price")
     * @SWG\Post(summary="Post to URL",
     *     @SWG\Parameter(name="body",in="body",required=true,
     *          @SWG\Schema(
     *              @SWG\Property(property="product_price",type="object",
     *                      @SWG\Property(property="product_code", type="string",example="10089879870879",maximum=16,),
     *                      @SWG\Property(property="currency", type="string",example="eur",maximum=3),
     *                      @SWG\Property(property="base_price", type="float",example="77.99"),
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(response=200,description="Creates a new price for the product"),
     * )
     */
    public function new(Request $request): Response
    {
        $price = $this->priceService->persistRequestData($request->request->all());
        $routeOptions = [
            'id' => $price->getId(),
            '_format' => $request->get('_format'),
        ];

        return new RedirectResponse($this->generateUrl('catalog_product_price_show', $routeOptions));
    }

    /**
     * @Route("/{id}", name="catalog_product_price_show", methods={"GET"})
     *
     * @SWG\Tag(name="price")
     * @SWG\Get(summary="Get to URL",
     *     @SWG\Response(response=200,description="Displays product price"),
     * )
     */
    public function show(Price $price): Response
    {
        return new JsonResponse($this->priceService->extract($price));
    }

    /**
     * @Route("/{id}/edit", name="catalog_product_price_edit", methods={"POST"})
     *
     * @SWG\Tag(name="price")
     * @SWG\Post(summary="Post to URL",
     *     @SWG\Parameter(name="body",in="body",required=true,
     *          @SWG\Schema(
     *              @SWG\Property(property="product_price",type="object",
     *                @SWG\Property(property="currency", type="string",example="eur",maximum=3),
     *                @SWG\Property(property="base_price", type="float",example="77.99"),
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(response=200,description="Edits product price"),
     * )
     * @todo refactor it, use service
     */
    public function edit(Request $request, Price $price): Response
    {
        $form = $this->createForm(PriceType::class, $price);
        $data = $request->request->all();
        if (empty($data['product_price'])) {
            throw new BadRequestException('Value of product_price can not be empty');
        }
        $form->submit($data['product_price'], false);

        if (!$form->isValid()) {
            throw new BadRequestException($form->getErrors(true, false));
        }
        $this->getDoctrine()->getManager()->flush();

        $routeOptions = [
            'id' => $price->getId(),
            '_format' => $request->get('_format'),
        ];

        return new RedirectResponse($this->generateUrl('catalog_product_price_show', $routeOptions));

    }

    /**
     * @Route("/{id}", name="catalog_product_price_delete", methods={"DELETE"})
     *
     * @SWG\Tag(name="price")
     * @SWG\Delete(summary="Delete to URL",
     *     @SWG\Response(response=200,description="Deletes product price"),
     * )
     * @todo refactor it, use service
     */
    public function delete(Request $request, Price $price): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($price);
        $entityManager->flush();

        return new JsonResponse();
    }
}
