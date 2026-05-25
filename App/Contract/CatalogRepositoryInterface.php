<?php

/**
 * Defines methods for retrieving catalog data
 * from the data source.
 */
namespace App\Contract;
interface CatalogRepositoryInterface extends BaseInterface
{
    
    // Get catalog items by category
    public function getCategoryCatalog($category, $limit = null, $offset = 0);

    // Search catalog items by keyword and category
    public function getSearchCatalog($search, $category = null, $limit = null, $offset = 0);

    // Get random catalog items
    public function getRandomCatalog();

    // Get a single catalog item by ID
    // public function getSingleItem($id);
}