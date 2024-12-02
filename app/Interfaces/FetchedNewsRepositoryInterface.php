<?php

namespace App\Interfaces;

interface FetchedNewsRepositoryInterface
{
    public function create(array $data): void;
}