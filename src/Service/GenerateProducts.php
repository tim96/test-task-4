<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Money\Currency;
use Money\Money;

class GenerateProducts
{
    const PRODUCT_NAME = 'product_';

    /** @var ProductRepository */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param int $count
     *
     * @return Product[]
     */
    public function execute(int $count): array
    {
        $products = [];
        for ($i = 1; $i <= $count; $i++) {
            $product = new Product();
            $product->setName(static::PRODUCT_NAME . $i);
            $product->setPrice(new Money(100, new Currency('RUB')));

            $this->productRepository->save($product);

            $products[] = $product;
        }

        return $products;
    }
}
