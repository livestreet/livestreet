{**
 * Навигация на странице активности
 *}

{component 'nav'
    name       = 'activity'
    activeItem = $sMenuItemSelect
    mods       = 'pills'
    items = [
        [ 'name' => 'user', 'url' => "{router page='stream'}personal/", 'text' => $aLang.activity.nav.personal, 'is_enabled' => !! $oUserCurrent ],
        [ 'name' => 'all',  'url' => "{router page='stream'}all/",  'text' => $aLang.activity.nav.all ]
    ]}