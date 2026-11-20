{extends file='layout.tpl'}
{block name=title}{$post.title} — Блог{/block}
{block name=content}
    <article class="post">
        <nav class="breadcrumbs"><a href="/">Главная</a> / {$post.title}</nav>
        <h1 class="page-title">{$post.title}</h1>
        <div class="post__meta">
            <span>{$post.published_at|date_format:'%d.%m.%Y'}</span>
            <span>{$post.views} просмотров</span>
            {foreach $post.categories as $category}
                <a class="tag" href="/category/{$category.slug|escape:'url'}">{$category.name}</a>
            {/foreach}
        </div>
        <img class="post__image" src="{$post.image}" alt="{$post.title}">
        <p class="lead">{$post.description}</p>
        <div class="post__content">{$post.content|nl2br}</div>
    </article>

    {if $similar}
        <section class="section">
            <h2 class="section__title">Похожие статьи</h2>
            <div class="grid">
                {foreach $similar as $post}
                    {include file='partials/post_card.tpl'}
                {/foreach}
            </div>
        </section>
    {/if}
{/block}
