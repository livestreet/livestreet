{**
 * Список пользователей (аватары)
 *
 * @param array $users      Список пользователей
 * @param array $pagination Массив с параметрами пагинации
 * @param array $emptyText
 *}

{component_define_params params=[ 'size' ]}

{$items = []}

{foreach $users as $user}
    {* TODO: Костыль для блогов *}
    {if $user->getUser()}{$user = $user->getUser()}{/if}

    {$items[] = {component 'user' template='avatar' size=$smarty.local.size|default:'small' user=$user}}
{/foreach}

{component 'avatar' template='list' items=$items params=$params}

{component 'pagination' total=+$pagination.iCountPage current=+$pagination.iCurrentPage url="{$pagination.sBaseUrl}/page__page__/"}