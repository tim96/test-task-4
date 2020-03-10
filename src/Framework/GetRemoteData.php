<?php declare(strict_types=1);

namespace App\Framework;

use App\Dto\ResponseDataDto;
use GuzzleHttp\Client;

class GetRemoteData
{
    public function sendGetRequest(string $url, array $parameters = []): ResponseDataDto
    {
        $client = new Client();

        $res = $client->request('GET', $url, $parameters);

        return new ResponseDataDto((int) $res->getStatusCode(), (string) $res->getBody());
    }
}
