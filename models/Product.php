<?php
namespace Models;

require_once __DIR__ . '/../db/Database.php';

use DB\Database;

class Product
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function create(string $title, ?string $description, ?string $mediaPath, int $createdBy): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO products (title, description, media_path, created_by, created_at) VALUES (:t, :d, :m, :c, NOW())');
        $stmt->execute([':t' => $title, ':d' => $description, ':m' => $mediaPath, ':c' => $createdBy]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, string $title, ?string $description, ?string $mediaPath): bool
    {
        $sql = 'UPDATE products SET title = :t, description = :d';
        if ($mediaPath !== null) $sql .= ', media_path = :m';
        $sql .= ' WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $params = [':t' => $title, ':d' => $description, ':id' => $id];
        if ($mediaPath !== null) $params[':m'] = $mediaPath;
        return $stmt->execute($params);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM products WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function listAll(int $limit = 100, int $offset = 0): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products ORDER BY created_at DESC LIMIT :l OFFSET :o');
        $stmt->bindValue(':l', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':o', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
