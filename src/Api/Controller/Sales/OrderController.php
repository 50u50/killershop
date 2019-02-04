<?php

namespace App\Api\Controller\Sales;

use App\Api\Entity\Sales\Order;
use App\Api\Entity\Sales\Order\Item;
use App\Api\Exception\BadRequestException;
use App\Api\Form\Sales\OrderType;
use App\Api\Service\Catalog\ProductService;
use App\Api\Service\DiscountService;
use App\Api\Service\MoneyService;
use App\Api\Service\Sales\OrderService;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/sales/order")
 */
class OrderController extends AbstractController
{
    /**
     * @var ProductService
     */
    private $productService;
    /**
     * @var OrderService
     */
    private $orderService;
    /**
     * @var DiscountService
     */
    private $discountService;

    /**
     * OrderController constructor.
     * @param ProductService $productService
     * @param OrderService $orderService
     * @param DiscountService $discountService
     */
    public function __construct(
        ProductService $productService,
        OrderService $orderService,
        DiscountService $discountService
    )
    {
        $this->productService = $productService;
        $this->orderService = $orderService;
        $this->discountService = $discountService;
    }

    /**
     * @todo refactor it, use service
     *
     * @Route("/new", name="sales_order_new", methods={"POST"})
     *
     * @SWG\Tag(name="order")
     * @SWG\Post(summary="Post to URL",
     *     @SWG\Parameter(name="body",in="body",required=true,
     *          @SWG\Schema(
     *             @SWG\Property(property="customer_email",type="string",example="johndoe@mail.com",maximum="255"),
     *             @SWG\Property(property="order_items",type="object",example={99989879870800: 15, 10089879870879: 2}),
     *          ),
     *     ),
     *     @SWG\Response(response=200,description="Submits customer order"),
     * )
     */
    public function new(Request $request): Response
    {
        $requestData = $request->request->all();
        if (empty($requestData['customer_email'])) {
            throw new BadRequestException('Customer email can not be empty.');
        }
        if (empty($requestData['order_items'])) {
            throw new BadRequestException('Array of "items" can not be empty.');
        }
        $products = $this->productService->findByCodes(array_keys($requestData['order_items']));
        if (empty($products)) {
            throw new BadRequestException('No Products found');
        }
        /**
         * @todo Exception on items diff found
         */
        $items = [];
        $total = 0;
        $order = new Order();

        foreach ($products as $product) {
            $qty = $requestData['order_items'][$product->getCode()];
            $this->discountService->setDiscountPrice($product);
            $subtotal = $product->getDiscountPrice() * $qty;
            $total += $subtotal;
            $item = new Item();
            $item
                ->setProductCode($product->getCode())
                ->setProductName($product->getName())
                ->setProductBrand($product->getBrand())
                ->setQuantity($qty)
                ->setSubtotal($subtotal)
                ->setSalesOrder($order);
            $items[] = $item;
        }

        $form = $this->createForm(OrderType::class, $order);

        $now = new \DateTime();
        $order->setItems($items);
        $order->setSubmitted($now);;
        $requestData['total'] = $total;
        $requestData['currency'] = MoneyService::DEFAULT_CURRENCY_CODE;
        $requestData['status'] = Order::STATUS_SUBMITTED;
        $requestData['submitted'] = $order->getSubmitted();

        $form->submit($requestData);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

        }

        $routeOptions = [
            'id' => $order->getId(),
            '_format' => $request->get('_format'),
        ];

        return new RedirectResponse($this->generateUrl('sales_order_show', $routeOptions));
    }

    /**
     * @Route("/{id}", name="sales_order_show", methods={"GET"})
     *
     * @SWG\Tag(name="order")
     * @SWG\Get(summary="Get to URL",
     *     @SWG\Response(response=200,description="Displays sales order"),
     * )
     */
    public function show(Request $request, $id): Response
    {
        $data = $this->orderService->exportData($id);

        return new JsonResponse($data);

    }
}
