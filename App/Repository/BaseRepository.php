<?php

namespace App\Repository;

use PDO;
use App\Contract\BaseInterface;

abstract class BaseRepository implements BaseInterface
{
    protected PDO $db;

    protected string $table;

    protected string $primaryKey = 'id';

    protected string $model;

    protected function mapToModel(array $data): object
    {
        return $this->model::fromArray($data);
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOM SELECT COLUMNS
    |--------------------------------------------------------------------------
    | Example:
    | protected ?string $selectColumns =
    |     "id, name, email";
    |
    | OR with alias:
    | "u.id, u.name, c.category_name"
    |--------------------------------------------------------------------------
    */
    protected ?string $selectColumns = null;

    /*
    |--------------------------------------------------------------------------
    | STORED PROCEDURES
    |--------------------------------------------------------------------------
    */
    protected ?string $getByIdProcedure = null;

    protected ?string $findAllProcedure = null;

    protected ?string $countProcedure = null;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /*
    |--------------------------------------------------------------------------
    | FIND ALL
    |--------------------------------------------------------------------------
    */
    public function findAll(?int $limit = null, int $offset = 0): array
    {
        if ($this->findAllProcedure !== null) {

            $stmt = $this->db->prepare(
                "CALL {$this->findAllProcedure}(?, ?)"
            );

            $stmt->bindValue(
                1,
                $limit,
                $limit === null
                ? PDO::PARAM_NULL
                : PDO::PARAM_INT
            );

            $stmt->bindValue(2, $offset, PDO::PARAM_INT);

        } else {

            $columns = $this->selectColumns ?? '*';

            $sql = "SELECT {$columns} FROM {$this->table}";

            if ($limit !== null) {
                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->db->prepare($sql);

            if ($limit !== null) {

                $stmt->bindValue(
                    ':limit',
                    $limit,
                    PDO::PARAM_INT
                );

                $stmt->bindValue(
                    ':offset',
                    $offset,
                    PDO::PARAM_INT
                );
            }
        }

        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt->closeCursor();

        return array_map(
            fn(array $row) => $this->mapToModel($row),
            $rows
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FIND BY ID
    |--------------------------------------------------------------------------
    */
    public function findById(int $id): ?object
    {
        if ($this->getByIdProcedure !== null) {

            $stmt = $this->db->prepare(
                "CALL {$this->getByIdProcedure}(?)"
            );

            $stmt->bindValue(1, $id, PDO::PARAM_INT);

        } else {

            $columns = $this->selectColumns ?? '*';

            $stmt = $this->db->prepare(
                "SELECT {$columns}
             FROM {$this->table}
             WHERE {$this->primaryKey} = :id
             LIMIT 1"
            );

            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        }

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt->closeCursor();

        if (!$data) {
            return null;
        }

        return $this->mapToModel($data);
    }
    /*
    |--------------------------------------------------------------------------
    | COUNT
    |--------------------------------------------------------------------------
    */
    public function count(array $filters = []): int
    {
        $search = $filters['search'] ?? null;
        $category = $filters['category'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | USE STORED PROCEDURE
        |--------------------------------------------------------------------------
        */
        if ($this->countProcedure !== null) {

            $stmt = $this->db->prepare(
                "CALL {$this->countProcedure}(:search, :category)"
            );

            $stmt->bindValue(
                ':search',
                $search,
                $search === null
                ? PDO::PARAM_NULL
                : PDO::PARAM_STR
            );

            $stmt->bindValue(
                ':category',
                $category,
                $category === null
                ? PDO::PARAM_NULL
                : PDO::PARAM_STR
            );

        } else {

            /*
            |--------------------------------------------------------------------------
            | DEFAULT COUNT QUERY
            |--------------------------------------------------------------------------
            */
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE 1=1";

            if ($search !== null) {
                $sql .= " AND title LIKE :search";
            }

            if ($category !== null) {
                $sql .= " AND category = :category";
            }

            $stmt = $this->db->prepare($sql);

            if ($search !== null) {
                $stmt->bindValue(
                    ':search',
                    "%{$search}%",
                    PDO::PARAM_STR
                );
            }

            if ($category !== null) {
                $stmt->bindValue(
                    ':category',
                    $category,
                    PDO::PARAM_STR
                );
            }
        }

        $stmt->execute();

        $count = (int) $stmt->fetchColumn();

        $stmt->closeCursor();

        return $count;
    }
}