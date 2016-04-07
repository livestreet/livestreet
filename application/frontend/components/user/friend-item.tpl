{**
 * Добавление / удаление из друзей
 *}

{$component = 'user-friend'}
{component_define_params params=[ 'friendship', 'tag', 'userTarget', 'mods', 'classes', 'attributes' ]}

{block 'user_friend_options'}
    {$tag = $tag|default:'li'}

    {if $friendship}
        {$status        = $friendship->getFriendStatus()}
        {$userCurrentId = $oUserCurrent->getId()}
        {$userToId      = $friendship->getUserTo()}

        {* Добавлен *}
        {if $status == $USER_FRIEND_ACCEPT + $USER_FRIEND_OFFER || $status == $USER_FRIEND_ACCEPT + $USER_FRIEND_ACCEPT}
            {$status = 'added'}

        {* Ожидает подтверждения *}
        {elseif ( $friendship->getStatusTo() == $USER_FRIEND_REJECT && $friendship->getStatusFrom() == $USER_FRIEND_OFFER && $userToId == $userCurrentId )
                || ( $status == $USER_FRIEND_OFFER + $USER_FRIEND_NULL && $userCurrentId == $userToId )}
            {$status = 'pending'}

        {* Приглашение отклонено *}
        {elseif $status == $USER_FRIEND_OFFER + $USER_FRIEND_REJECT && $userToId != $userCurrentId}
            {$status = 'rejected'}

        {* Приглашение отправлено *}
        {elseif $status == $USER_FRIEND_OFFER + $USER_FRIEND_NULL && $userCurrentId == $friendship->getUserFrom()}
            {$status = 'sent'}

        {* Текущий пользователь удалил из друзей target пользователя, *}
        {* но предложение target пользователя еще в силе *}
        {else}
            {$status = 'linked'}
        {/if}

    {* Добавить в друзья *}
    {else}
        {$status = 'notfriends'}
    {/if}
{/block}


<{$tag} class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes} data-status="{$status}" data-target="{$userTarget->getId()}">
    {block 'user_friend'}
        {if in_array( $status, [ 'sent', 'rejected' ] )}
            <span class="{$component}-text js-user-friend-text">{lang name="user.friends.status.{$status}"}</span>
        {else}
            <a href="#" class="{$component}-text js-user-friend-text">{lang name="user.friends.status.{$status}"}</a>
        {/if}
    {/block}
</{$tag}>