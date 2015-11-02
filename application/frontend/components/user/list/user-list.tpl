{**
 * Список пользователей
 *
 * @param array   $users
 * @param array   $pagination
 * @param boolean $useMore
 * @param boolean $hideMore
 * @param string  $textEmpty
 *}

{if $smarty.local.users}
    {$pagination = $smarty.local.pagination}

    {* Список пользователей *}
    {component 'item' template='group'
        classes = 'js-more-users-container'
        items   = {component 'user' template='list-loop' users=$smarty.local.users}}

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
    {component 'blankslate' text=$smarty.local.textEmpty|default:{lang name='user.notices.empty'}}
{/if}