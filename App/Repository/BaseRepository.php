<?php

namespace App\Repository;

use PDO;

abstract class BaseRepository
{
    protected PDO $db;

    protected string $table;

    protected string $primaryKey = 'id';

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    function findAll($limit = null, $offset = 0)
    {
        $result = $this->db->prepare(" CALL sp_get_full_catalog ( ? , ? )");

        $result->bindParam(
            1,
            $limit,
            $limit === null ? PDO::PARAM_NULL : PDO::PARAM_INT
        );

        $result->bindParam(2, $offset, PDO::PARAM_INT);

        $result->execute();

        $catalog = $result->fetchAll();

        $result->closeCursor();

        return $catalog;
    }
    function findById($id)
    {
        $result = $this->db->prepare("CALL sp_get_item_full_detail (?)"); // use prepare() function

        $result->bindParam(1, $id, PDO::PARAM_INT);

        $result->execute();

        $item = $result->fetch(PDO::FETCH_ASSOC);

        // Return null if item does not exist
        if ($item === false) {
            $result->closeCursor();
            return null;
        }

        $result->nextRowset();

        // Load related people data (actors, authors, etc.)
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $item[strtolower($row['role'])][] = $row['fullname'];
        }

        $result->closeCursor();

        return $item;
    }
}