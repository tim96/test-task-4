<?php declare(strict_types=1);

namespace App\Framework;

use App\Controller\IndexController;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

class Routing
{
    public function getRouteCollection(): RouteCollection
    {
        $routes = new RouteCollection();
        $routes->add('default', new Route('/', [
            '_controller' => [new IndexController(), 'index'],
        ]));

        $route = new Route('/api/orders', [
            '_controller' => 'App\Controller\Api\OrderController::createOrderAction',
        ]);
        $route->setMethods(['POST']);
        $routes->add('orders_create_order', $route);

        $route = new Route('/api/orders/pay', [
            '_controller' => 'App\Controller\Api\OrderController::payOrderAction',
        ]);
        $route->setMethods(['POST']);
        $routes->add('orders_pay_order', $route);

        $route = new Route('/api/products', [
            '_controller' => 'App\Controller\Api\ProductController::generateProductsAction',
        ]);
        $route->setMethods(['POST']);
        $routes->add('products_generate_data', $route);

        $route = new Route('/api/products', [
            '_controller' => 'App\Controller\Api\ProductController::getAllProductsAction',
        ]);
        $route->setMethods(['GET']);
        $routes->add('products_list', $route);

        return $routes;
    }
}
