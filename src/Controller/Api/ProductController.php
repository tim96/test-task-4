<?php declare(strict_types=1);

namespace App\Controller\Api;

use App\Service\GenerateProducts;
use App\Service\GetAllProducts;
use App\Service\Transformer\ProductsTransformer;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductController
{
    /** @var GenerateProducts */
    private $generateProducts;

    /** @var GetAllProducts */
    private $getAllProducts;

    /** @var ProductsTransformer */
    private $productsTransformer;

    public function __construct(
        GenerateProducts $generateProducts,
        GetAllProducts $getAllProducts,
        ProductsTransformer $productsTransformer
    ) {
        $this->generateProducts = $generateProducts;
        $this->getAllProducts = $getAllProducts;
        $this->productsTransformer = $productsTransformer;
    }

    public function getAllProductsAction(): JsonResponse
    {
        $products = $this->getAllProducts->execute();

        $results = $this->productsTransformer->execute($products);

        return new JsonResponse($results);
    }

    public function generateProductsAction(): JsonResponse
    {
        $this->generateProducts->execute(20);

        return new JsonResponse();
    }
}
