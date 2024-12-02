<?php

namespace App\Interfaces;

use App\Models\News;
use Illuminate\Support\Collection;

interface NewsRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?News;
    public function create(array $data): void;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}