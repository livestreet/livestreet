{**
 * Навигация на странице пользователя
 *}

{component 'nav' name='user' activeItem=$sMenuProfileItemSelect mods='stacked' classes='user-nav' hookParams=[ 'oUserProfile' => $oUserProfile ] items=[
	[ 'name' => 'whois',      'text' => {lang name='user.profile.nav.info'},         'url' => "{$oUserProfile->getUserWebPath()}" ],
	[ 'name' => 'wall',       'text' => {lang name='user.profile.nav.wall'},         'url' => "{$oUserProfile->getUserWebPath()}wall/", 'count' => $iCountWallUser ],
	[ 'name' => 'created',    'text' => {lang name='user.profile.nav.publications'}, 'url' => "{$oUserProfile->getUserWebPath()}created/topics/", 'count' => $iCountCreated ],
	[ 'name' => 'favourites', 'text' => {lang name='user.profile.nav.favourite'},    'url' => "{$oUserProfile->getUserWebPath()}favourites/topics/", 'count' => $iCountFavourite ],
	[ 'name' => 'friends',    'text' => {lang name='user.profile.nav.friends'},      'url' => "{$oUserProfile->getUserWebPath()}friends/", 'count' => $iCountFriendsUser ],
	[ 'name' => 'activity',   'text' => {lang name='user.profile.nav.activity'},     'url' => "{$oUserProfile->getUserWebPath()}stream/" ],
	[ 'name' => 'talk',       'text' => {lang name='user.profile.nav.messages'},     'url' => "{router page='talk'}", 'count' => $iUserCurrentCountTalkNew, 'is_enabled' => $oUserCurrent && $oUserCurrent->getId() == $oUserProfile->getId() ],
	[ 'name' => 'settings',   'text' => {lang name='user.profile.nav.settings'},     'url' => "{router page='settings'}", 'is_enabled' => $oUserCurrent && $oUserCurrent->getId() == $oUserProfile->getId() ]
]}