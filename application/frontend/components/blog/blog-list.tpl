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
    {capture 'blog_list'}
        {foreach $smarty.local.blogs as $blog}
            {include './blog-list-item.tpl' blog=$blog}
        {/foreach}
    {/capture}

    {component 'item' template='group' classes='js-more-blogs-container' items=$smarty.capture.blog_list}

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
    {component 'alert' text=(($smarty.local.textEmpty) ? $smarty.local.textEmpty : $aLang.blog.alerts.empty) mods='empty'}
{/if}