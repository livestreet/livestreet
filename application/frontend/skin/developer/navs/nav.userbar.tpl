{if $oUserCurrent}
	{$aItems = [
		[
			'text'       => "<img src=\"{$oUserCurrent->getProfileAvatarPath(24)}\" alt=\"{$oUserCurrent->getDisplayName()}\" class=\"avatar\" /> {$oUserCurrent->getDisplayName()}",
			'url'        => "{router page='content'}add/topic",
			'classes'    => 'nav-item--userbar-username',
			'menu'       => [
				[ 'name' => 'whois',      'text' => $aLang.user_menu_profile_whois,      'url' => "{$oUserCurrent->getUserWebPath()}" ],
				[ 'name' => 'wall',       'text' => $aLang.user_menu_profile_wall,       'url' => "{$oUserCurrent->getUserWebPath()}wall/", 'count' => $iCountWallUser ],
				[ 'name' => 'created',    'text' => $aLang.user_menu_publication,        'url' => "{$oUserCurrent->getUserWebPath()}created/topics/", 'count' => $iCountCreated ],
				[ 'name' => 'favourites', 'text' => $aLang.user_menu_profile_favourites, 'url' => "{$oUserCurrent->getUserWebPath()}favourites/topics/", 'count' => $iCountFavourite ],
				[ 'name' => 'friends',    'text' => $aLang.user_menu_profile_friends,    'url' => "{$oUserCurrent->getUserWebPath()}friends/", 'count' => $iCountFriendsUser ],
				[ 'name' => 'stream',     'text' => $aLang.user_menu_profile_stream,     'url' => "{$oUserCurrent->getUserWebPath()}stream/" ],
				[ 'name' => 'talk',       'text' => $aLang.talk.title,                   'url' => "{router page='talk'}", 'count' => $iUserCurrentCountTalkNew, 'is_enabled' => $oUserCurrent && $oUserCurrent->getId() == $oUserCurrent->getId() ],
				[ 'name' => 'settings',   'text' => $aLang.settings_menu,                'url' => "{router page='settings'}", 'is_enabled' => $oUserCurrent && $oUserCurrent->getId() == $oUserCurrent->getId() ],
				[ 'name' => 'admin',      'text' => $aLang.admin_title,                  'url' => "{router page='admin'}", 'is_enabled' => $oUserCurrent && $oUserCurrent->isAdministrator() ]
			]
		],
		[ 'text' => $aLang.block_create,         'url' => "{router page='content'}add/topic", 'attributes' => 'data-modal-target="modal-write"' ],
		[ 'text' => $aLang.user_privat_messages, 'url' => "{router page='talk'}", 'title' => $aLang.user_privat_messages_new, 'is_enabled' => $iUserCurrentCountTalkNew, 'count' => $iUserCurrentCountTalkNew ],
		[ 'text' => $aLang.exit,                 'url' => "{router page='login'}exit/?security_ls_key={$LIVESTREET_SECURITY_KEY}" ]
	]}
{else}
	{$aItems = [
		[ 'text' => $aLang.auth.login.title,        'classes' => 'js-modal-toggle-login',        'url' => {router page='login'} ],
		[ 'text' => $aLang.auth.registration.title, 'classes' => 'js-modal-toggle-registration', 'url' => {router page='registration'} ]
	]}
{/if}

{include 'components/nav/nav.tpl' sName='userbar' sActiveItem=$sMenuHeadItemSelect sMods='userbar' aItems=$aItems}