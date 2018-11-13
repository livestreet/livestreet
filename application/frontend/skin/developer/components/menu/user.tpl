{**
 * Меню пользователя
 *
 * 
 *}
{component 'nav.item' 
    isRoot    = true 
    text        = "<img src=\"{$oUserCurrent->getProfileAvatarPath(24)}\" alt=\"{$oUserCurrent->getDisplayName()}\" class=\"avatar\" /> {$oUserCurrent->getDisplayName()}"
    url         = "{$oUserCurrent->getUserWebPath()}"
    classes     = 'ls-nav-item--userbar-username'
    menu        = $params}
