<?php declare(strict_types=1);

namespace App\Controller\Api;

use App\Service\CreateOrder;
use App\Service\PayOrder;
use App\Service\RecalculateOrderSum;
use App\Service\Transformer\OrderTransformer;
use Money\Currency;
use Money\Money;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class OrderController
{
    /** @var CreateOrder */
    private $createOrder;

    /** @var RecalculateOrderSum */
    private $recalculateOrderSum;

    /** @var PayOrder */
    private $payOrder;

    /** @var OrderTransformer */
    private $orderTransformer;

    public function __construct(
        CreateOrder $createOrder,
        RecalculateOrderSum $recalculateOrderSum,
        PayOrder $payOrder,
        OrderTransformer $orderTransformer
    ) {
        $this->createOrder = $createOrder;
        $this->recalculateOrderSum = $recalculateOrderSum;
        $this->payOrder = $payOrder;
        $this->orderTransformer = $orderTransformer;
    }

    public function createOrderAction(Request $request): JsonResponse
    {
        $products = $this->getRequest($request);

        $order = $this->createOrder->execute($products);
        $order = $this->recalculateOrderSum->execute($order);

        return new JsonResponse($this->orderTransformer->execute($order));
    }

    public function payOrderAction(Request $request): JsonResponse
    {
        $orderData = $this->getRequest($request);

        if (!isset($orderData['sum']) || !isset($orderData['orderId'])) {
            throw new \InvalidArgumentException('Didn\'t find sum/orderId');
        }

        $order = $this->payOrder->execute(
            (int) $orderData['orderId'],
            new Money($orderData['sum'], new Currency('RUB'))
        );

        return new JsonResponse($this->orderTransformer->execute($order));
    }

    private function getRequest(Request $request): array
    {
        // Тут лучше использовать param converter но по условию его не получилось интегрировать
        $contentType = (string) $request->headers->get('Content-Type', '');
        if (0 !== strpos($contentType, 'application/json')) {
            throw new \InvalidArgumentException('Content should be json');
        }

        // Вместе c param converter тут можно было бы использовать jms + validator
        $content = (string) $request->getContent();
        $data = json_decode($content, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException('Content should be valid json');
        }

        return $data;
    }
}
