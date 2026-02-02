<?php
namespace Models;

require_once __DIR__ . '/../db/Database.php';

use DB\Database;

class Pages
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function create(string $slug, string $title, ?string $content, ?string $mediaPath, int $createdBy): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO pages (slug, title, content, media_path, created_by, created_at) VALUES (:s, :t, :c, :m, :cb, NOW())');
        $stmt->execute([':s' => $slug, ':t' => $title, ':c' => $content, ':m' => $mediaPath, ':cb' => $createdBy]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, string $slug, string $title, ?string $content, ?string $mediaPath): bool
    {
        $sql = 'UPDATE pages SET slug = :s, title = :t, content = :c';
        if ($mediaPath !== null) $sql .= ', media_path = :m';
        $sql .= ' WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $params = [':s' => $slug, ':t' => $title, ':c' => $content, ':id' => $id];
        if ($mediaPath !== null) $params[':m'] = $mediaPath;
        return $stmt->execute($params);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM pages WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM pages WHERE slug = :s LIMIT 1');
        $stmt->execute([':s' => $slug]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM pages WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function listAll(int $limit = 100, int $offset = 0): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM pages ORDER BY created_at DESC LIMIT :l OFFSET :o');
        $stmt->bindValue(':l', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':o', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
