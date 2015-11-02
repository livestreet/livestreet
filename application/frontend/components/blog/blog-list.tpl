{**
 * Список блогов
 *
 * @param array   $blogs
 * @param array   $pagination
 * @param boolean $useMore
 * @param boolean $hideMore
 * @param string  $textEmpty
 *}

{if $smarty.local.blogs}
    {$paging = $smarty.local.pagination}

    {* Список блогов *}
    {component 'item' template='group'
        classes = 'js-more-blogs-container'
        items   = {component 'blog' template='list-loop' blogs=$smarty.local.blogs}}

    {* Кнопка подгрузки *}
    {if $smarty.local.useMore}
        {if ! $smarty.local.hideMore}
            {component 'more'
                classes    = 'js-more-search'
                target     = '.js-more-blogs-container'
                ajaxParams = [ 'next_page' => 2 ]}
        {/if}
    {else}
        {component 'pagination' total=+$paging.iCountPage current=+$paging.iCurrentPage url="{$paging.sBaseUrl}/page__page__/{$paging.sGetParams}"}
    {/if}
{else}
    {component 'blankslate' text=$smarty.local.textEmpty|default:{lang name='blog.alerts.empty'}}
{/if}