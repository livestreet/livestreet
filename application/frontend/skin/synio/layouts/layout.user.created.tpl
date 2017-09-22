{**
 * Публикации пользователя
 *}

{extends './layout.user.tpl'}

{block 'layout_options' append}
    {$layoutNav = [[
        hook       => 'profile_created',
        hookParams => [ 'oUserProfile' => $oUserProfile ],
        activeItem => $sMenuSubItemSelect,
        items => [
            [ 'name' => 'topics',   'url' => "{$oUserProfile->getUserWebPath()}created/topics/",   'text' => {lang name='user.publications.nav.topics'}, 'count' => $iCountTopicUser ],
            [ 'name' => 'comments', 'url' => "{$oUserProfile->getUserWebPath()}created/comments/", 'text' => {lang name='user.publications.nav.comments'}, 'count' => $iCountCommentUser ],
            [ 'name' => 'notes',    'url' => "{$oUserProfile->getUserWebPath()}created/notes/",    'text' => {lang name='user.publications.nav.notes'}, 'count' => $iCountNoteUser, 'is_enabled' => $oUserCurrent && $oUserCurrent->getId() == $oUserProfile->getId() ]
        ]
    ]]}
{/block}