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


{* Список блогов *}
{if $smarty.local.blogs}
    {if $smarty.local.searchCount}
        <h3 class="h3">{lang name='blog.search.result_title' count=$smarty.local.searchCount plural=true}</h3>
    {/if}

    {* Список блогов *}
    <ul class="object-list object-list-actions blog-list js-more-blogs-container">
        {foreach $smarty.local.blogs as $blog}
            {include './blog-list-item.tpl' blog=$blog}
        {/foreach}
    </ul>

    {* Кнопка подгрузки *}
    {if $smarty.local.useMore}
        {if ! $smarty.local.hideMore}
            {include 'components/more/more.tpl'
                classes    = 'js-more-search'
                target     = '.js-more-blogs-container'
                attributes = 'data-search-type="blogs" data-proxy-page-next="2"'}
        {/if}
    {else}
        {include 'components/pagination/pagination.tpl' paging=$smarty.local.pagination}
    {/if}
{else}
    {include 'components/alert/alert.tpl' text=(($smarty.local.textEmpty) ? $smarty.local.textEmpty : $aLang.blog.alerts.empty) mods='empty'}
{/if}