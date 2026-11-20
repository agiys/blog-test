<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{block name=title}Блог{/block}</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <header class="header">
        <div class="container header__inner">
            <a class="header__logo" href="/">Блог</a>
        </div>
    </header>
    <main class="container main">
        {block name=content}{/block}
    </main>
    <footer class="footer">
        <div class="container">&copy; {$smarty.now|date_format:'%Y'} Блог</div>
    </footer>
</body>
</html>
