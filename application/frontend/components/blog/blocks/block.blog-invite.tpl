{**
 * Приглашение пользователей в закрытый блог.
 * Выводится на странице администрирования пользователей закрытого блога.
 *}

{component 'blog' template='invite'
    users      = $blogUsersInvited
    classes    = 'js-user-list-add-blog-invite'
    attributes = [ 'data-param-target_id' => $blogEdit->getId() ]
    assign     = blockContent}

{component 'block'
    mods    = 'blog-invite'
    title   = {lang 'blog.invite.invite_users'}
    content = $blockContent}