<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    public function index(Request $request): Response
    {
        $file = file_get_contents(__DIR__ . '/../../templates/index.html');

        return new Response($file);
    }
}
