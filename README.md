# Blog

Блог с категориями и статьями на чистом PHP 8.1+, MySQL и Smarty.

## Запуск

```
cp .env.example .env
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php bin/seed.php
```

Сайт: http://localhost:8080

Схема БД (`database/schema.sql`) применяется автоматически при первом старте контейнера MySQL.

## Сидинг

`php bin/seed.php` пересоздаёт данные: 6 категорий (одна пустая) и 40 статей с привязкой к 1–3 категориям.

## Стили

SCSS лежит в `assets/scss`, скомпилированный CSS — в `public/assets/css`.

```
npm install
npm run build:css
```

## Структура

- `public/` — точка входа и статика
- `src/` — Router, View, Database, Seeder, репозитории и контроллеры
- `templates/` — шаблоны Smarty
- `database/` — схема БД
- `bin/` — CLI-скрипты
