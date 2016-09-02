{**
 * Список пользователей
 *
 * @param array   $users
 * @param array   $pagination
 * @param boolean $useMore
 * @param boolean $hideMore
 * @param string  $textEmpty
 *}

{component_define_params params=[ 'users', 'pagination', 'users', 'useMore', 'hideMore', 'textEmpty' ]}

{if $users}
    {* Список пользователей *}
    {component 'item.group' classes='user-list js-more-users-container' items={component 'user' template='list-loop' users=$users}}

    {* Кнопка подгрузки *}
    {if $useMore}
        {if ! $hideMore}
            {component 'more' classes='js-more-search' target='.js-more-users-container' ajaxParams=[ 'next_page' => 2 ]}
        {/if}
    {else}
        {component 'pagination' total=+$pagination.iCountPage current=+$pagination.iCurrentPage url="{$pagination.sBaseUrl}/page__page__/"}
    {/if}
{else}
    {component 'blankslate' text=$textEmpty|default:{lang name='user.notices.empty'}}
{/if}