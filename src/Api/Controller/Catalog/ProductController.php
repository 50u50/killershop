<?php

namespace App\Api\Controller\Catalog;

use App\Api\Entity\Catalog\Product;
use App\Api\Exception\BadRequestException;
use App\Api\Form\Catalog\ProductType;
use App\Api\Service\Catalog\ProductService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @Route("/api/catalog/product")
 */
class ProductController extends AbstractFOSRestController
{
    /**
     * @var ProductService
     */
    private $productService;

    public function __construct(ProductService $productService)
    {

        $this->productService = $productService;
    }

    /**
     * @Route("/list", name="catalog_product_list", methods={"GET"})
     * @return mixed
     *
     * @SWG\Tag(name="product")
     * @SWG\Get(summary="Get to URL",
     *     @SWG\Response(response=200,description="Displays product list (catalog)"),
     * )
     *
     * @todo Add pagination to the list
     */
    public function index(): Response
    {
        return new JsonResponse($this->productService->extractAll());
    }

    /**
     * @Route("/new", name="catalog_product_new", methods={"POST"})
     *
     * @SWG\Tag(name="product")
     * @SWG\Post(summary="Post to URL",
     *     @SWG\Parameter(name="body",in="body",required=true,
     *          @SWG\Schema(
     *                  @SWG\Property(property="product", type="object",
     *                        @SWG\Property(property="code", type="string",example="99989879870800",maximum=16),
     *                        @SWG\Property(property="name", type="string",example="Super bundle",maximum=255),
     *                        @SWG\Property(property="brand", type="string",example="NoName Inc.",maximum=255),
     *                   ),
     *                    @SWG\Property(property="product_price", type="object",
     *                      @SWG\Property(property="currency", type="string",example="eur",maximum=3),
     *                      @SWG\Property(property="base_price", type="float",example="77.99"),
     *                     ),
     *                  @SWG\Property(property="price_discount", type="object",
     *                      @SWG\Property(property="rule", type="string",example="percentage",maximum=16),
     *                      @SWG\Property(property="value", type="decimal",example="25"),
     *                  ),
     *                  @SWG\Property(property="bundled_products", type="string",example="10089879870879,10089879870880"),
     *          ),
     *     ),
     *     @SWG\Response(response=200,description="Creates a new product and associated entities (optional)"),
     * )
     */
    public function new(Request $request): Response
    {
        $product = $this->productService->persistRequestData($request->request->all());

        $routeOptions = [
            'id' => $product->getId(),
            '_format' => $request->get('_format'),
        ];

        return new RedirectResponse($this->generateUrl('catalog_product_show', $routeOptions));
    }


    /**
     * @Route("/{id}", name="catalog_product_show", methods={"GET"})
     *
     * @SWG\Tag(name="product")
     * @SWG\Get(summary="Get to URL",
     *     @SWG\Response(response=200,description="Displays generic product data"),
     * )
     */
    public function show(Product $product): Response
    {
        return new JsonResponse($this->productService->extract($product));
    }

    /**
     * @Route("/{id}/edit", name="catalog_product_edit", methods={"POST"})
     *
     * @SWG\Tag(name="product")
     * @SWG\Post(summary="Post to URL",
     *     @SWG\Parameter(name="body",in="body",required=true,
     *          @SWG\Schema(
     *              @SWG\Property(property="product", type="object",
     *                  @SWG\Property(property="code", type="string",example="99989879870800",maximum=16),
     *                  @SWG\Property(property="name", type="string",example="Super bundle",maximum=255),
     *                  @SWG\Property(property="brand", type="string",example="NoName Inc.",maximum=255),
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(response=200,description="Edits generic product data"),
     * )
     */
    public function edit(Request $request, Product $product): Response
    {
        $data = $request->request->all();
        if (empty($data['product'])) {
            throw new BadRequestException('product data can not be empty');
        }
        $form = $this->createForm(ProductType::class, $product);
        $form->submit($data['product'], false);
        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
        }
        $routeOptions = [
            'id' => $product->getId(),
            '_format' => $request->get('_format'),
        ];

        return new RedirectResponse($this->generateUrl('catalog_product_show', $routeOptions));
    }

    /**
     * @Route("/{id}", name="catalog_product_delete", methods={"DELETE"})
     *
     * @SWG\Tag(name="product")
     * @SWG\Delete(summary="Delete to URL",
     *     @SWG\Response(response=200,description="Deletes product"),
     * )
     */
    public function delete(Request $request, Product $product): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        return new JsonResponse();
    }
}
