{if $pages > 1}
<nav class="pagination">
    {if $page > 1}
        <a class="pagination__link" href="?sort={$sort}&amp;page={$page-1}">&larr;</a>
    {/if}
    {section name=i loop=$pages}
        {assign var=n value=$smarty.section.i.iteration}
        {if $n == $page}
            <span class="pagination__link pagination__link--active">{$n}</span>
        {else}
            <a class="pagination__link" href="?sort={$sort}&amp;page={$n}">{$n}</a>
        {/if}
    {/section}
    {if $page < $pages}
        <a class="pagination__link" href="?sort={$sort}&amp;page={$page+1}">&rarr;</a>
    {/if}
</nav>
{/if}
