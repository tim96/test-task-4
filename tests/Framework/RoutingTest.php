<?php declare(strict_types=1);

namespace App\Tests\Frameworks;

use App\Framework\Routing;
use PHPUnit\Framework\TestCase;

class RoutingTest extends TestCase
{
    public function testCollection(): void
    {
        $routing = new Routing();

        $result = $routing->getRouteCollection();

        $this->assertEquals(5, $result->count());
    }
}
