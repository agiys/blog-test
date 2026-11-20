<?php

declare(strict_types=1);

namespace App;

use PDO;

final class Seeder
{
    private const CATEGORIES = [
        ['Технологии', 'technology', 'Новости и обзоры из мира IT.'],
        ['Путешествия', 'travel', 'Маршруты, заметки и советы путешественникам.'],
        ['Кулинария', 'cooking', 'Рецепты и кулинарные эксперименты.'],
        ['Спорт', 'sport', 'Тренировки, соревнования и здоровый образ жизни.'],
        ['Наука', 'science', 'Открытия и исследования простым языком.'],
        ['Пустая категория', 'empty', 'Категория без статей, на главной не отображается.'],
    ];

    private const POSTS_COUNT = 40;

    public function __construct(private PDO $pdo)
    {
    }

    public function run(): void
    {
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
        foreach (['category_post', 'posts', 'categories'] as $table) {
            $this->pdo->exec("TRUNCATE TABLE $table");
        }
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

        $this->pdo->beginTransaction();
        $categoryIds = $this->seedCategories();
        $this->seedPosts($categoryIds);
        $this->pdo->commit();
    }

    private function seedCategories(): array
    {
        $stmt = $this->pdo->prepare('INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)');
        $ids = [];

        foreach (self::CATEGORIES as [$name, $slug, $description]) {
            $stmt->execute([$name, $slug, $description]);
            if ($slug !== 'empty') {
                $ids[] = (int) $this->pdo->lastInsertId();
            }
        }

        return $ids;
    }

    private function seedPosts(array $categoryIds): void
    {
        $postStmt = $this->pdo->prepare(
            'INSERT INTO posts (title, slug, image, description, content, views, published_at)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $linkStmt = $this->pdo->prepare('INSERT INTO category_post (category_id, post_id) VALUES (?, ?)');

        mt_srand(42);

        for ($i = 1; $i <= self::POSTS_COUNT; $i++) {
            $slug = "post-$i";
            $postStmt->execute([
                "Статья №$i",
                $slug,
                "https://picsum.photos/seed/$slug/800/450",
                "Краткое описание статьи №$i.",
                $this->content($i),
                mt_rand(0, 5000),
                date('Y-m-d H:i:s', strtotime('-' . ($i * 7) . ' hours')),
            ]);
            $postId = (int) $this->pdo->lastInsertId();

            $keys = array_rand($categoryIds, mt_rand(1, 3));
            foreach ((array) $keys as $key) {
                $linkStmt->execute([$categoryIds[$key], $postId]);
            }
        }
    }

    private function content(int $number): string
    {
        $paragraph = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt '
            . 'ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco '
            . 'laboris nisi ut aliquip ex ea commodo consequat.';

        return implode("\n\n", array_fill(0, 4, "Статья №$number. $paragraph"));
    }
}
