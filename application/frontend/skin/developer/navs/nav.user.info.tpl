{**
 * Навигация на главной странице профиля
 *}

{component 'nav'
    name       = 'profile_info'
    activeItem = $sMenuSubItemSelect
    mods       = 'pills'
    hookParams = [ 'oUserProfile' => $oUserProfile ]
    items      = [ [ 'text' => {lang name='user.profile.title'}, 'url' => $oUserProfile->getUserWebPath(), 'name' => 'main' ] ]}