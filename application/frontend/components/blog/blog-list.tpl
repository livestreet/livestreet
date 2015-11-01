{**
 * Список блогов
 *
 * @param array   $blogs
 * @param array   $pagination
 * @param boolean $useMore
 * @param boolean $hideMore
 * @param integer $searchCount
 * @param string  $textEmpty
 *}

{if $smarty.local.blogs}
    {$paging = $smarty.local.pagination}

    {* Заголовок *}
    {if $smarty.local.searchCount}
        <h3 class="h3">{lang name='blog.search.result_title' count=$smarty.local.searchCount plural=true}</h3>
    {/if}

    {* Список блогов *}
    {component 'item' template='group' classes='js-more-blogs-container' items={component 'blog' template='list-loop' blogs=$smarty.local.blogs}}

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
    {component 'blankslate' text=(($smarty.local.textEmpty) ? $smarty.local.textEmpty : $aLang.blog.alerts.empty)}
{/if}