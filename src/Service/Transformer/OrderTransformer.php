<?php declare(strict_types=1);

namespace App\Service\Transformer;

use App\Entity\Order;

class OrderTransformer
{
    /**
     * @param Order $order
     * @return array
     */
    public function execute(Order $order): array
    {
        $result['orderId'] = $order->getId();
        $result['sum'] = $order->getSum()->getAmount();
        $result['status'] = $order->getStatus();

        return $result;
    }
}
