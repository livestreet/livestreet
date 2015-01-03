{**
 * Навигация в профиле пользователя в разделе "Избранное"
 *}

{component 'nav'
    name       = 'profile_favourite'
    activeItem = $sMenuSubItemSelect
    mods       = 'pills'
    hookParams = [ 'oUserProfile' => $oUserProfile ]
    items = [
        [ 'name' => 'topics',   'text' => {lang name='user.favourites.nav.topics'},   'url'  => "{$oUserProfile->getUserWebPath()}favourites/topics/",   'count' => $iCountTopicFavourite ],
        [ 'name' => 'comments', 'text' => {lang name='user.favourites.nav.comments'}, 'url'  => "{$oUserProfile->getUserWebPath()}favourites/comments/", 'count' => $iCountCommentFavourite ]
    ]}