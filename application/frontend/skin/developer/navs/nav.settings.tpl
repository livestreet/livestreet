{**
 * Навигация на странице настроек
 *}

{component 'nav'
    name       = 'settings'
    activeItem = $sMenuSubItemSelect
    mods       = 'pills'
    items = [
        [ 'url' => "{router page='settings'}profile/", 'text' => {lang name='user.settings.nav.profile'}, 'name' => 'profile' ],
        [ 'url' => "{router page='settings'}account/", 'text' => {lang name='user.settings.nav.account'}, 'name' => 'account' ],
        [ 'url' => "{router page='settings'}tuning/",  'text' => {lang name='user.settings.nav.tuning'},  'name' => 'tuning' ],
        [ 'url' => "{router page='settings'}invite/",  'text' => {lang name='user.settings.nav.invites'}, 'name' => 'invite' ]
    ]}