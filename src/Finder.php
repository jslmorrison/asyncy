<?php

namespace App;

interface Finder
{
    public function find(int $id): array;
    public function findAll(): array;
}