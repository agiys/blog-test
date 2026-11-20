{extends file='layout.tpl'}
{block name=title}{$category.name} — Блог{/block}
{block name=content}
    <nav class="breadcrumbs"><a href="/">Главная</a> / {$category.name}</nav>
    <h1 class="page-title">{$category.name}</h1>
    <p class="lead">{$category.description}</p>

    <div class="toolbar">
        <span>Статей: {$total}</span>
        <div class="sort">
            <span>Сортировка:</span>
            <a class="sort__link{if $sort == 'date'} sort__link--active{/if}" href="?sort=date">По дате</a>
            <a class="sort__link{if $sort == 'views'} sort__link--active{/if}" href="?sort=views">По просмотрам</a>
        </div>
    </div>

    <div class="grid">
        {foreach $posts as $post}
            {include file='partials/post_card.tpl'}
        {foreachelse}
            <p>В этой категории пока нет статей.</p>
        {/foreach}
    </div>

    {include file='partials/pagination.tpl'}
{/block}
