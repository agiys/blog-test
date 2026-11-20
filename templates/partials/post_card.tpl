<article class="card">
    <a class="card__image" href="/post/{$post.slug|escape:'url'}">
        <img src="{$post.image}" alt="{$post.title}" loading="lazy">
    </a>
    <div class="card__body">
        <div class="card__meta">
            <span>{$post.published_at|date_format:'%d.%m.%Y'}</span>
            <span>{$post.views} просмотров</span>
        </div>
        <h3 class="card__title">
            <a href="/post/{$post.slug|escape:'url'}">{$post.title}</a>
        </h3>
        <p class="card__text">{$post.description}</p>
    </div>
</article>
