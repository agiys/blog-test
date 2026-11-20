<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;
use PDO;

final class PostRepository
{
    private const SORTS = [
        'date' => 'p.published_at DESC, p.id DESC',
        'views' => 'p.views DESC, p.published_at DESC',
    ];

    public function paginateByCategory(int $categoryId, string $sort, int $page, int $perPage): array
    {
        $pdo = Database::connection();
        $orderBy = self::SORTS[$sort] ?? self::SORTS['date'];

        $count = $pdo->prepare('SELECT COUNT(*) FROM category_post WHERE category_id = :id');
        $count->execute(['id' => $categoryId]);
        $total = (int) $count->fetchColumn();

        $stmt = $pdo->prepare(
            "SELECT p.id, p.title, p.slug, p.image, p.description, p.views, p.published_at
             FROM posts p
             JOIN category_post cp ON cp.post_id = p.id
             WHERE cp.category_id = :id
             ORDER BY $orderBy
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue('id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue('offset', ($page - 1) * $perPage, PDO::PARAM_INT);
        $stmt->execute();

        return [$stmt->fetchAll(), $total];
    }

    public function findBySlug(string $slug): ?array
    {
        $pdo = Database::connection();

        $stmt = $pdo->prepare('SELECT * FROM posts WHERE slug = :slug');
        $stmt->execute(['slug' => $slug]);
        $post = $stmt->fetch();

        if (!$post) {
            return null;
        }

        $stmt = $pdo->prepare(
            'SELECT c.id, c.name, c.slug FROM categories c
             JOIN category_post cp ON cp.category_id = c.id
             WHERE cp.post_id = :id
             ORDER BY c.name'
        );
        $stmt->execute(['id' => $post['id']]);
        $post['categories'] = $stmt->fetchAll();

        return $post;
    }

    public function incrementViews(int $id): void
    {
        $stmt = Database::connection()->prepare('UPDATE posts SET views = views + 1 WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function similar(int $postId, int $limit): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT p.id, p.title, p.slug, p.image, p.description, p.views, p.published_at,
                    COUNT(*) AS shared
             FROM posts p
             JOIN category_post cp ON cp.post_id = p.id
             WHERE p.id <> :id
               AND cp.category_id IN (SELECT category_id FROM category_post WHERE post_id = :id2)
             GROUP BY p.id
             ORDER BY shared DESC, p.published_at DESC
             LIMIT :limit'
        );
        $stmt->bindValue('id', $postId, PDO::PARAM_INT);
        $stmt->bindValue('id2', $postId, PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
