<?php

namespace App\Service;

use App\Contract\CatalogRepositoryInterface;
use App\Repository\CatalogRepository;

class CatalogService extends BaseService
{
    private CatalogRepositoryInterface $repo;

    public function __construct(?CatalogRepositoryInterface $repo = null)
    {
        // fallback ONLY for small projects (keeps flexibility)
        $this->repo = $repo ?? new CatalogRepository($this->db());
    }

    /* ================= HOME PAGE ================= */

    public function getHomePageData(): array
    {
        return [
            'random' => $this->repo->getRandomCatalog(),
            'pageTitle' => 'Personal Media Library',
            'section' => 'catalog'
        ];
    }

    /* ================= CATALOG PAGE ================= */

    public function getCatalogPage(array $queryParams): array
    {
        $section = $this->getCategory($queryParams);

        $search = $this->getSearchTerm($queryParams);
        $currentPage = $this->getCurrentPage($queryParams);

        $totalItems = $this->repo->count([
            'category' => $section,
            'search' => $search
        ]);

        $pagination = $this->buildPagination($totalItems, $currentPage);

        $catalog = $this->loadCatalogData(
            $section,
            $search,
            $pagination['limit'],
            $pagination['offset']
        );

        return [
            'catalog' => $catalog,
            'section' => $section,
            'search' => $search,
            'currentPage' => $pagination['currentPage'],
            'totalPages' => $pagination['totalPages'],
            'pageTitle' => $this->buildPageTitle($section),
            'queryString' => $this->buildQueryString($section, $search)
        ];
    }

    /* ================= SINGLE ITEM ================= */

    public function getSingleItem(int $id): ?array
    {
        return $this->repo->getSingleItem($id);

    }


    /* ================= PRIVATE HELPERS ================= */

    private function getCategory(array $params): ?string
    {
        $allowed = ['books', 'movies', 'music'];

        $category = $params['cat'] ?? null;

        return ($category && in_array($category, $allowed, true))
            ? $category
            : null;
    }

    private function getSearchTerm(array $params): ?string
    {
        $search = trim($params['s'] ?? '');

        return $search !== '' ? $search : null;
    }

    private function getCurrentPage(array $params): int
    {
        $page = filter_var($params['pg'] ?? 1, FILTER_VALIDATE_INT);

        return ($page === false || $page < 1) ? 1 : $page;
    }

    private function buildPagination(int $totalItems, int $currentPage): array
    {
        $itemsPerPage = 8;

        $totalPages = max(1, (int) ceil($totalItems / $itemsPerPage));

        if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
        }

        return [
            'limit' => $itemsPerPage,
            'offset' => ($currentPage - 1) * $itemsPerPage,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ];
    }

    private function loadCatalogData(
        ?string $section,
        ?string $search,
        int $limit,
        int $offset
    ): array {
        if ($search && $section) {
            return $this->repo->getSearchCatalog($search, $section, $limit, $offset);
        }

        if ($search) {
            return $this->repo->getSearchCatalog($search, null, $limit, $offset);
        }

        if ($section) {
            return $this->repo->getCategoryCatalog($section, $limit, $offset);
        }

        return $this->repo->findAll($limit, $offset);
    }

    private function buildPageTitle(?string $section): string
    {
        return $section ? ucfirst($section) : 'Full Catalog';
    }

    private function buildQueryString(?string $section, ?string $search): string
    {
        $params = [];

        if ($section) {
            $params[] = 'cat=' . urlencode($section);
        }

        if ($search) {
            $params[] = 's=' . urlencode($search);
        }

        return implode('&', $params);
    }
}