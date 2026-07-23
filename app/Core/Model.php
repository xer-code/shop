<?php

namespace App\Core;

/**
 * Model — Base class wrapping PDO with common CRUD methods
 * All queries use prepared statements — no raw interpolation
 */
abstract class Model
{
    protected static string $table = '';
    protected static string $primaryKey = 'id';
    protected Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Find a record by primary key
     */
    public static function find(int $id): ?array
    {
        $db = Database::getInstance();
        $table = static::$table;
        $pk = static::$primaryKey;
        $stmt = $db->query("SELECT * FROM {$table} WHERE {$pk} = ? LIMIT 1", [$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Find all records
     */
    public static function findAll(string $orderBy = 'id', string $direction = 'DESC', int $limit = 0): array
    {
        $db = Database::getInstance();
        $table = static::$table;
        $sql = "SELECT * FROM {$table} ORDER BY {$orderBy} {$direction}";
        if ($limit > 0) {
            $sql .= " LIMIT {$limit}";
        }
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Get all records (alias for findAll)
     */
    public static function all(string $orderBy = 'id', string $direction = 'DESC', int $limit = 0): array
    {
        return static::findAll($orderBy, $direction, $limit);
    }

    /**
     * Find records matching conditions
     */
    public static function where(array $conditions, string $orderBy = 'id', string $direction = 'DESC', int $limit = 0, int $offset = 0): array
    {
        $db = Database::getInstance();
        $table = static::$table;
        $clauses = [];
        $params = [];
        
        foreach ($conditions as $column => $value) {
            if (is_array($value)) {
                // Handle operators: ['column' => ['>=', 5]]
                $clauses[] = "{$column} {$value[0]} ?";
                $params[] = $value[1];
            } else {
                $clauses[] = "{$column} = ?";
                $params[] = $value;
            }
        }

        $whereStr = implode(' AND ', $clauses);
        $sql = "SELECT * FROM {$table} WHERE {$whereStr} ORDER BY {$orderBy} {$direction}";
        if ($limit > 0) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        $stmt = $db->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Find the first record matching conditions
     */
    public static function whereFirst(array $conditions): ?array
    {
        $results = static::where($conditions, 'id', 'ASC', 1);
        return $results[0] ?? null;
    }

    /**
     * Count records matching conditions
     */
    public static function count(array $conditions = []): int
    {
        $db = Database::getInstance();
        $table = static::$table;
        
        if (empty($conditions)) {
            $stmt = $db->query("SELECT COUNT(*) as count FROM {$table}");
        } else {
            $clauses = [];
            $params = [];
            foreach ($conditions as $column => $value) {
                if (is_array($value)) {
                    $clauses[] = "{$column} {$value[0]} ?";
                    $params[] = $value[1];
                } else {
                    $clauses[] = "{$column} = ?";
                    $params[] = $value;
                }
            }
            $whereStr = implode(' AND ', $clauses);
            $stmt = $db->query("SELECT COUNT(*) as count FROM {$table} WHERE {$whereStr}", $params);
        }
        
        return (int) $stmt->fetch()['count'];
    }

    /**
     * Create a new record
     */
    public static function create(array $data): int
    {
        $db = Database::getInstance();
        $table = static::$table;
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $db->query(
            "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})",
            array_values($data)
        );
        
        return (int) $db->lastInsertId();
    }

    /**
     * Update a record by primary key
     */
    public static function update(int $id, array $data): bool
    {
        $db = Database::getInstance();
        $table = static::$table;
        $pk = static::$primaryKey;
        
        $setClauses = [];
        $params = [];
        foreach ($data as $column => $value) {
            $setClauses[] = "{$column} = ?";
            $params[] = $value;
        }
        $params[] = $id;
        
        $setStr = implode(', ', $setClauses);
        $stmt = $db->query("UPDATE {$table} SET {$setStr} WHERE {$pk} = ?", $params);
        
        return $stmt->rowCount() > 0;
    }

    /**
     * Delete a record by primary key
     */
    public static function delete(int $id): bool
    {
        $db = Database::getInstance();
        $table = static::$table;
        $pk = static::$primaryKey;
        $stmt = $db->query("DELETE FROM {$table} WHERE {$pk} = ?", [$id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Execute a raw query with prepared statements
     */
    public static function raw(string $sql, array $params = []): \PDOStatement
    {
        $db = Database::getInstance();
        return $db->query($sql, $params);
    }

    /**
     * Search with LIKE
     */
    public static function search(string $column, string $term, int $limit = 50, int $offset = 0): array
    {
        $db = Database::getInstance();
        $table = static::$table;
        $stmt = $db->query(
            "SELECT * FROM {$table} WHERE {$column} LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?",
            ["%{$term}%", $limit, $offset]
        );
        return $stmt->fetchAll();
    }
}
