<?php

namespace App\Contract;

interface BaseInterface
{

    public function create(array $data): bool;

    public function findAll(int $limit = null, int $offset = 0): array;

    public function findById(int $id);

    public function Count(array $filters = []): int;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}