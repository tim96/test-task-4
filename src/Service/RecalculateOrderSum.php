<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderProduct;
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

        /** @var OrderProduct[] $orderProducts */
        $orderProducts = $order->getOrderProducts();
        foreach ($orderProducts as $orderProduct) {
            $total = $total->add($orderProduct->getSum());
        }

        $order->setSum($total);

        $this->orderRepository->save($order);

        return $order;
    }
}
