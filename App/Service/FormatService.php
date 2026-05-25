<?php

namespace App\Service;

use App\Contract\FormatRepositoryInterface;
use App\Repository\FormatRepository;

class FormatService extends BaseService
{
    private FormatRepositoryInterface $repo;

    public function __construct(?FormatRepositoryInterface $repo = null)
    {
        $this->repo = $repo ?? new FormatRepository($this->db());
    }

    public function format_array($category = null): array
    {
        return $this->repo->get_format_drop_down($category);
    }

    public function category_drop_down(): array
    {
        return $this->repo->get_category_drop_down();
    }

    public function genres_array($category = null): array
    {
        return $this->repo->get_genres_drop_down($category);
    }
}