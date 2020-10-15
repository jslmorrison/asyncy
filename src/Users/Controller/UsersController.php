<?php

namespace App\Users\Controller;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

final class UsersController
{
    public function __invoke(ServerRequestInterface $request): Response
    {
        return new Response(
            200,
            [
                'Content-Type' => 'application/json',
                json_encode(['message' => 'Response from GET users collection'])
            ]
        );
    }
}
