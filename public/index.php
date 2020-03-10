<?php declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use App\Framework\RestApi;
use App\Framework\Routing;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__.'/../.env');

if (isset($_ENV['APP_DEBUG']) && filter_var($_ENV['APP_DEBUG'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)) {
    umask(0000);

    Debug::enable();
}

$routes = (new Routing())->getRouteCollection();
$kernel = new RestApi($routes);

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);
