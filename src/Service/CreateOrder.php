<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Entity\Product;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;

class CreateOrder
{
    /** @var OrderRepository */
    private $orderRepository;

    /** @var ProductRepository */
    private $productRepository;

    public function __construct(
        OrderRepository $orderRepository,
        ProductRepository $productRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    public function execute(array $products): Order
    {
        $productIds = array_filter(array_map(function (array $data) {
            return $data['id'];
        }, $products));

        $products = $this->productRepository->findByIds($productIds);
        if (count($products) < count($productIds)) {
            throw new \InvalidArgumentException('Didn\'t find some products');
        }

        $order = new Order();

        /** @var Product $product */
        foreach ($products as $product) {
            $order->addProduct($product);

            $product->addOrder($order);
        }

        $this->orderRepository->save($order);

        return $order;
    }
}
