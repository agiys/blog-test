<?php

declare(strict_types=1);

namespace App;

final class Config
{
    public static function env(string $key, string $default = ''): string
    {
        $value = getenv($key);

        return $value === false ? $default : $value;
    }

    public static function basePath(string $path = ''): string
    {
        return dirname(__DIR__) . ($path !== '' ? '/' . ltrim($path, '/') : '');
    }
}
