<?php

namespace App\Api\Service\Sales;

use App\Api\Repository\Sales\OrderRepository;

class OrderService
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;
    /**
     * @var OrderHydrator
     */
    private $orderHydrator;

    public function __construct(
        OrderRepository $orderRepository,
        OrderHydrator $orderHydrator
    )
    {

        $this->orderRepository = $orderRepository;
        $this->orderHydrator = $orderHydrator;
    }

    public function exportData(string $orderId): array
    {
//        $id = (int)substr($orderId, -2);
        $order = $this->orderRepository->findOneWithChildren($orderId);

        return $this->orderHydrator->extractRow($order);
    }
}
