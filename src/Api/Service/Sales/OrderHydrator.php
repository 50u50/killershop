<?php

namespace App\Api\Service\Sales;

use App\Api\Entity\Sales\Order;
use App\Api\Service\MoneyService as MoneyService;

class OrderHydrator
{
    /**
     * @var MoneyService
     */
    private $moneyService;

    public function __construct(
        MoneyService $moneyService
    )
    {
        $this->moneyService = $moneyService;
    }

    public function extractRow(Order $order)
    {
        $items = [];
        foreach ($order->getItems() as $item) {
            $items[] = [
                'code' => $item->getProductCode(),
                'product_name' => $item->getProductName(),
                'product_brand' => $item->getProductBrand(),
                'quantity' => $item->getQuantity(),
                'subtotal' => $item->getSubtotal(),
            ];
        }

        return [
            'order_number' => $order->getId(),
            'customer_email' => $order->getCustomerEmail(),
            'total' => $this->moneyService->getPriceString($order->getTotal()),
            'status' => $order->getStatus(),
            'order_items' => $items,
        ];
    }
}
