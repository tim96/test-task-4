<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Framework\GetRemoteData;
use App\Repository\OrderRepository;
use Money\Money;
use Symfony\Component\HttpFoundation\Response;

class PayOrder
{
    const PAYMENT_URL = 'ya.ru';

    /** @var OrderRepository */
    private $orderRepository;

    /** @var GetRemoteData */
    private $getRemoteData;

    public function __construct(OrderRepository $orderRepository, GetRemoteData $requestData)
    {
        $this->orderRepository = $orderRepository;
        $this->getRemoteData = $requestData;
    }

    public function execute(int $orderId, Money $sum): Order
    {
        /** @var Order|null $order */
        $order = $this->orderRepository->find($orderId);
        if (!$order) {
            throw new \InvalidArgumentException('Order not found by id' . $orderId);
        }

        /** @var Money $orderSum */
        $orderSum = $order->getSum();
        if ($orderSum->equals($sum) && $order->isNew()) {
            $data = $this->getRemoteData->sendGetRequest(static::PAYMENT_URL);
            if ($data->getStatusCode() === Response::HTTP_OK) {
                $order->setPaid();

                $this->orderRepository->save($order);
            }
        }

        return $order;
    }
}
