<?php declare(strict_types=1);

namespace App\Service\Transformer;

use App\Entity\Product;

class ProductsTransformer
{
    /**
     * @param Product[] $products
     * @return array
     */
    public function execute(array $products): array
    {
        $results = [];

        foreach ($products as $product) {
            $item = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice()->getAmount(),
            ];

            $results[] = $item;
        }

        return $results;
    }
}
