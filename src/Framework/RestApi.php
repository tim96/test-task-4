<?php declare(strict_types=1);

namespace App\Framework;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\Controller\ContainerControllerResolver;
use Symfony\Component\HttpKernel\EventListener\ErrorListener;
use Symfony\Component\HttpKernel\EventListener\ResponseListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class RestApi extends HttpKernel
{
    public function __construct(RouteCollection $routes)
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->setParameter('database_url', $_ENV['DATABASE_URL']);
        $containerBuilder->setParameter('database_driver', $_ENV['DATABASE_DRIVER']);
        $containerBuilder->setParameter('project_dir', __DIR__ . '/../../');

        $loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__));
        $loader->load('services.yaml');

        $matcher = new UrlMatcher($routes, new RequestContext());

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new RouterListener($matcher, new RequestStack()));
        $dispatcher->addSubscriber(new ResponseListener('UTF-8'));

        $errorHandler = function (FlattenException $exception) {
            return new JsonResponse([
                'error' => $exception->getMessage(),
                'code' => $exception->getStatusCode(),
            ], $exception->getStatusCode());
        };
        $dispatcher->addSubscriber(new ErrorListener($errorHandler));

        $controllerResolver = new ContainerControllerResolver($containerBuilder);
        $argumentResolver = new ArgumentResolver();

        parent::__construct($dispatcher, $controllerResolver, new RequestStack(), $argumentResolver);
    }
}
