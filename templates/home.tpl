{extends file='layout.tpl'}
{block name=title}Блог{/block}
{block name=content}
    <h1 class="page-title">Категории</h1>
    {foreach $categories as $category}
        <section class="section">
            <div class="section__head">
                <div>
                    <h2 class="section__title">{$category.name}</h2>
                    <p class="section__text">{$category.description}</p>
                </div>
                <a class="button" href="/category/{$category.slug|escape:'url'}">Все статьи</a>
            </div>
            <div class="grid">
                {foreach $category.posts as $post}
                    {include file='partials/post_card.tpl'}
                {/foreach}
            </div>
        </section>
    {foreachelse}
        <p>Статей пока нет.</p>
    {/foreach}
{/block}
