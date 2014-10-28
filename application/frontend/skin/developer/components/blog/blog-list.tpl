{**
 * Список блогов
 *
 * @styles css/blog.css
 * @scripts <frontend>/common/js/blog.js
 *}


{* Список блогов *}
{if $aBlogs}
    {if $iSearchCount}
        <h3 class="h3">{lang name='blog.search.result_title' count=$iSearchCount plural=true}</h3>
    {/if}

    {* Список блогов *}
    <ul class="object-list object-list-actions blog-list js-more-blogs-container">
        {foreach $aBlogs as $blog}
            {include './blog-list-item.tpl' blog=$blog}
        {/foreach}
    </ul>

    {* Кнопка подгрузки *}
    {if $bUseMore}
        {if ! $bHideMore}
            {include 'components/more/more.tpl'
                classes    = 'js-more-search'
                target     = '.js-more-blogs-container'
                attributes = 'data-search-type="blogs" data-proxy-page-next="2"'}
        {/if}
    {else}
        {include 'components/pagination/pagination.tpl' aPaging=$aPaging}
    {/if}
{else}
    {include 'components/alert/alert.tpl' text=(($sBlogsEmptyList) ? $sBlogsEmptyList : $aLang.blog.alerts.empty) mods='empty'}
{/if}