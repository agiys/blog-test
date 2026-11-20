<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Router;
use App\View;

final class CategoryController
{
    private const PER_PAGE = 6;

    public function __construct(
        private View $view,
        private Router $router,
        private CategoryRepository $categories,
        private PostRepository $posts
    ) {
    }

    public function show(string $slug): void
    {
        $category = $this->categories->findBySlug($slug);

        if ($category === null) {
            $this->router->notFound();
            return;
        }

        $sort = ($_GET['sort'] ?? 'date') === 'views' ? 'views' : 'date';
        $page = max(1, (int) ($_GET['page'] ?? 1));

        [$posts, $total] = $this->posts->paginateByCategory((int) $category['id'], $sort, $page, self::PER_PAGE);
        $pages = max(1, (int) ceil($total / self::PER_PAGE));

        if ($page > $pages) {
            $this->router->notFound();
            return;
        }

        echo $this->view->render('category.tpl', [
            'category' => $category,
            'posts' => $posts,
            'sort' => $sort,
            'page' => $page,
            'pages' => $pages,
            'total' => $total,
        ]);
    }
}
