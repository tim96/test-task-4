<?php declare(strict_types=1);

namespace App\Service;

use App\Repository\ProductRepository;

class GetAllProducts
{
    /** @var ProductRepository */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function execute(): array
    {
        return $this->productRepository->findAll();
    }
}
