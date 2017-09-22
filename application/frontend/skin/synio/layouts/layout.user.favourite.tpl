{**
 * Избранное пользователя
 *}

{extends './layout.user.tpl'}

{block 'layout_options' append}
    {$layoutNav = [[
        hook       => 'profile_created',
        hookParams => [ 'oUserProfile' => $oUserProfile ],
        activeItem => $sMenuSubItemSelect,
        items => [
            [ 'name' => 'topics',   'text' => {lang name='user.favourites.nav.topics'},   'url'  => "{$oUserProfile->getUserWebPath()}favourites/topics/",   'count' => $iCountTopicFavourite ],
            [ 'name' => 'comments', 'text' => {lang name='user.favourites.nav.comments'}, 'url'  => "{$oUserProfile->getUserWebPath()}favourites/comments/", 'count' => $iCountCommentFavourite ]
        ]
    ]]}
{/block}