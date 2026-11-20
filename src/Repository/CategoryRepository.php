<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;

final class CategoryRepository
{
    public function findBySlug(string $slug): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM categories WHERE slug = :slug');
        $stmt->execute(['slug' => $slug]);

        return $stmt->fetch() ?: null;
    }

    public function withLatestPosts(int $limit): array
    {
        $pdo = Database::connection();

        $categories = $pdo->query(
            'SELECT c.* FROM categories c
             WHERE EXISTS (SELECT 1 FROM category_post cp WHERE cp.category_id = c.id)
             ORDER BY c.name'
        )->fetchAll();

        if ($categories === []) {
            return [];
        }

        $stmt = $pdo->prepare(
            'SELECT t.* FROM (
                SELECT p.id, p.title, p.slug, p.image, p.description, p.views, p.published_at, cp.category_id,
                       ROW_NUMBER() OVER (PARTITION BY cp.category_id ORDER BY p.published_at DESC, p.id DESC) AS rn
                FROM posts p
                JOIN category_post cp ON cp.post_id = p.id
            ) t
            WHERE t.rn <= :limit
            ORDER BY t.category_id, t.rn'
        );
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        $grouped = [];
        foreach ($stmt->fetchAll() as $row) {
            $grouped[$row['category_id']][] = $row;
        }

        foreach ($categories as &$category) {
            $category['posts'] = $grouped[$category['id']] ?? [];
        }

        return $categories;
    }
}
