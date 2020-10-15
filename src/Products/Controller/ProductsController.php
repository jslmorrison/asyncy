<?php

namespace App\Products\Controller;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

final class ProductsController
{
    public function __invoke(ServerRequestInterface $request): Response
    {
        return new Response(
            200,
            [
                'Content-Type' => 'application/json',
                json_encode(['message' => 'Response from GET products collection'])
            ]
        );
    }
}