<?php declare(strict_types=1);

namespace App\Tests\Frameworks;

use App\Framework\RestApi;
use App\Framework\Routing;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

class RestApiTest extends TestCase
{
    public function setUp(): void
    {
        $dotenv = new Dotenv();
        $dotenv->loadEnv(__DIR__.'/../../.env.test');

        parent::setUp();
    }

    public function testDefaultAction(): void
    {
        $routes = (new Routing())->getRouteCollection();
        $kernel = new RestApi($routes);

        $response = $kernel->handle(new Request());

        $this->assertEquals(200, $response->getStatusCode());
    }
}
