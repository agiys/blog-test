<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\View;

final class HomeController
{
    private const POSTS_PER_CATEGORY = 3;

    public function __construct(
        private View $view,
        private CategoryRepository $categories
    ) {
    }

    public function index(): void
    {
        echo $this->view->render('home.tpl', [
            'categories' => $this->categories->withLatestPosts(self::POSTS_PER_CATEGORY),
        ]);
    }
}
