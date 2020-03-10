<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Entity\Product;
use App\Repository\OrderRepository;
use Money\Currency;
use Money\Money;

class RecalculateOrderSum
{
    /** @var OrderRepository */
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function execute(Order $order): Order
    {
        $total = new Money(0, new Currency('RUB'));

        /** @var Product $product */
        foreach ($order->getProducts()->toArray() as $product) {
            $total = $total->add($product->getPrice());
        }

        $order->setSum($total);

        $this->orderRepository->save($order);

        return $order;
    }
}
