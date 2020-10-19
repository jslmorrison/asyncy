<?php

namespace App\Users\Controller;

use App\Finder;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

final class UsersController
{
    private $finder;

    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    public function __invoke(ServerRequestInterface $request): Response
    {
        $users = $this->finder
            ->findAll();
        return new Response(
            200,
            [
                'Content-Type' => 'application/json',
                json_encode(['users' => $users])
            ]
        );
    }
}
