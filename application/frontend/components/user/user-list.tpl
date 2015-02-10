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
    {$pagination = $smarty.local.pagination}

    {* Заголовок *}
    {if $smarty.local.searchCount}
        <h3 class="h3">{lang name='user.search.result_title' count=$smarty.local.searchCount plural=true}</h3>
    {/if}

    {* Список пользователей *}
    {capture 'user_list'}
        {include './user-list-loop.tpl' users=$smarty.local.users}
    {/capture}

    {component 'item' template='group' classes='js-more-users-container' items=$smarty.capture.user_list}

    {* Кнопка подгрузки *}
    {if $smarty.local.useMore}
        {if ! $smarty.local.hideMore}
            {component 'more'
                classes    = 'js-more-search'
                target     = '.js-more-users-container'
                ajaxParams = [ 'next_page' => 2 ]}
        {/if}
    {else}
        {component 'pagination' total=+$pagination.iCountPage current=+$pagination.iCurrentPage url="{$pagination.sBaseUrl}/page__page__/"}
    {/if}
{else}
    {component 'alert' text=$smarty.local.textEmpty|default:{lang name='user.notices.empty'} mods='empty'}
{/if}