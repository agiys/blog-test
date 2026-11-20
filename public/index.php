<?php

declare(strict_types=1);

use App\Controller\CategoryController;
use App\Controller\HomeController;
use App\Controller\PostController;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Router;
use App\View;

require dirname(__DIR__) . '/vendor/autoload.php';

$view = new View();
$router = new Router();
$categories = new CategoryRepository();
$posts = new PostRepository();

$home = new HomeController($view, $categories);
$category = new CategoryController($view, $router, $categories, $posts);
$post = new PostController($view, $router, $posts);

$router->get('/', [$home, 'index']);
$router->get('/category/{slug}', [$category, 'show']);
$router->get('/post/{slug}', [$post, 'show']);

$router->dispatch($_SERVER['REQUEST_URI'] ?? '/');
