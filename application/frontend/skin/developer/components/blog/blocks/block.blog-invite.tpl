{**
 * Приглашение пользователей в закрытый блог.
 * Выводится на странице администрирования пользователей закрытого блога.
 *}

{extends 'Component@block.block'}

{block 'block_title'}
    {$aLang.blog.invite.invite_users}
{/block}

{block 'block_options' append}
    {$mods = "{$mods} blog-invite"}
{/block}

{block 'block_content'}
    {component 'blog' template='invite'
        users      = $blogUsersInvited
        classes    = 'js-user-list-add-blog-invite'
        attributes = [ 'data-param-target_id' => $blogEdit->getId() ]}
{/block}