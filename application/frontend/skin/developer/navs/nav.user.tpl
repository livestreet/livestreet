{**
 * Навигация на странице пользователя
 * TODO: В бекенде проставить sMenuProfileItemSelect
 *}

{include 'components/nav/nav.tpl' sName='user' sActiveItem=$sMenuProfileItemSelect sMods='pills stacked' aHookArguments=[ 'oUserProfile' => $oUserProfile ] aItems=[
	[ 'name' => 'whois',      'text' => $aLang.user_menu_profile_whois,      'url' => "{$oUserProfile->getUserWebPath()}" ],
	[ 'name' => 'wall',       'text' => $aLang.user_menu_profile_wall,       'url' => "{$oUserProfile->getUserWebPath()}wall/", 'count' => $iCountWallUser ],
	[ 'name' => 'created',    'text' => $aLang.user_menu_publication,        'url' => "{$oUserProfile->getUserWebPath()}created/topics/", 'count' => $iCountCreated ],
	[ 'name' => 'favourites', 'text' => $aLang.user_menu_profile_favourites, 'url' => "{$oUserProfile->getUserWebPath()}favourites/topics/", 'count' => $iCountFavourite ],
	[ 'name' => 'friends',    'text' => $aLang.user_menu_profile_friends,    'url' => "{$oUserProfile->getUserWebPath()}friends/", 'count' => $iCountFriendsUser ],
	[ 'name' => 'stream',     'text' => $aLang.user_menu_profile_stream,     'url' => "{$oUserProfile->getUserWebPath()}stream/" ],
	[ 'name' => 'talk',       'text' => $aLang.talk_menu_inbox,              'url' => "{router page='talk'}", 'count' => $iUserCurrentCountTalkNew, 'is_enabled' => $oUserCurrent and $oUserCurrent->getId() == $oUserProfile->getId() ],
	[ 'name' => 'settings',   'text' => $aLang.settings_menu,                'url' => "{router page='settings'}", 'is_enabled' => $oUserCurrent and $oUserCurrent->getId() == $oUserProfile->getId() ]
]}