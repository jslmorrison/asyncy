<?php

namespace App\Users\Finder;

use App\Finder;

final class MysqlUserFinder implements Finder
{
    public function __construct()
    {
    }

    public function findAll(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Jimmy Wong',
                'email' => 'jwong@example.com',
                'active' => true
            ],
            [
                'id' => 2,
                'name' => 'Hans Blix',
                'email' => 'hblix@example.com',
                'active' => true
            ],
            [
                'id' => 3,
                'name' => 'Johnny Mo',
                'email' => 'jmo@example.com',
                'active' => true
            ],
            [
                'id' => 4,
                'name' => 'Algernon Carruthers',
                'email' => 'ac@example.com',
                'active' => false
            ]
        ];
    }

    public function find(int $id): array
    {
        return [];
    }
}

