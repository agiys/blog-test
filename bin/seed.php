<?php

declare(strict_types=1);

use App\Database;
use App\Seeder;

require dirname(__DIR__) . '/vendor/autoload.php';

(new Seeder(Database::connection()))->run();

echo "Seeding completed\n";
