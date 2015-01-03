{**
 * Список пользователей
 *
 * @param array   $users
 * @param array   $pagination
 * @param boolean $useMore
 * @param boolean $hideMore
 * @param integer $searchCount
 * @param string  $textEmpty
 *}

{if $smarty.local.users}
    {* Заголовок *}
    {if $smarty.local.searchCount}
        <h3 class="h3">{lang name='user.search.result_title' count=$smarty.local.searchCount plural=true}</h3>
    {/if}

    {* Список пользователей *}
    <ul class="object-list user-list js-more-users-container">
        {include './user-list-loop.tpl' users=$smarty.local.users}
    </ul>

    {* Кнопка подгрузки *}
    {if $smarty.local.useMore}
        {if ! $smarty.local.hideMore}
            {component 'more'
                classes    = 'js-more-search'
                target     = '.js-more-users-container'
                attributes = [ 'data-search-type' => 'users', 'data-proxy-page-next' => '2' ]}
        {/if}
    {else}
        {component 'pagination' paging=$smarty.local.pagination}
    {/if}
{else}
    {component 'alert' text=$smarty.local.textEmpty|default:{lang name='user.notices.empty'} mods='empty'}
{/if}