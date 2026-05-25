<?php

namespace App\Contract;

interface BaseInterface
{
    // public function Count($category = null, $search = null);

     public function Count(array $filters = []): int;
    public function findById(int $id);

    public function findAll(int $limit = null, int $offset = 0);
}