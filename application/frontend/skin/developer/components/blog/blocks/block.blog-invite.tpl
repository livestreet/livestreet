{**
 * Приглашение пользователей в закрытый блог.
 * Выводится на странице администрирования пользователей закрытого блога.
 *}

{extends 'components/block/block.tpl'}

{block 'block_title'}
    {$aLang.blog.invite.invite_users}
{/block}

{block 'block_options' append}
    {$mods = "{$mods} blog-invite"}
{/block}

{block 'block_content'}
    {include 'components/blog/invite/invite.tpl'
        users      = $aBlogUsersInvited
        classes    = 'js-user-list-add-blog-invite'
        attributes = [ 'data-param-i-target-id' => $oBlogEdit->getId() ]}
{/block}