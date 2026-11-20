<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PostRepository;
use App\Router;
use App\View;

final class PostController
{
    private const SIMILAR_LIMIT = 3;

    public function __construct(
        private View $view,
        private Router $router,
        private PostRepository $posts
    ) {
    }

    public function show(string $slug): void
    {
        $post = $this->posts->findBySlug($slug);

        if ($post === null) {
            $this->router->notFound();
            return;
        }

        $this->posts->incrementViews((int) $post['id']);
        $post['views']++;

        echo $this->view->render('post.tpl', [
            'post' => $post,
            'similar' => $this->posts->similar((int) $post['id'], self::SIMILAR_LIMIT),
        ]);
    }
}
