{**
 * Профиль пользователя с информацией о нем
 *
 * @param array  usersInvited
 * @param object invitedByUser
 * @param array  blogsJoined
 * @param array  blogsModerate
 * @param array  blogsAdminister
 * @param array  blogsCreated
 * @param array  usersFriend
 *}

{extends 'layouts/layout.user.tpl'}

{block 'layout_content' append}
    {component 'user' template='info'
        user            = $oUserProfile
        friends         = $userFriends
        usersInvited    = $usersInvited
        invitedByUser   = $invitedByUser
        blogsJoined     = $blogsJoined
        blogsAdminister = $blogsAdminister
        blogsModerate   = $blogsModerate
        blogsCreated    = $blogsCreated}
{/block}