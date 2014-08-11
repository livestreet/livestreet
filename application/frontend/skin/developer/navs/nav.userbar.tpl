{if $oUserCurrent}
	{$aItems = [
		[
			'text'       => "<img src=\"{$oUserCurrent->getProfileAvatarPath(24)}\" alt=\"{$oUserCurrent->getDisplayName()}\" class=\"avatar\" /> {$oUserCurrent->getDisplayName()}",
			'url'        => "{router page='content'}add/topic",
			'classes'    => 'nav-item--userbar-username',
			'menu'       => [
				[ 'name' => 'whois',      'text' => {lang name='user.profile.nav.info'},         'url' => "{$oUserCurrent->getUserWebPath()}" ],
				[ 'name' => 'wall',       'text' => {lang name='user.profile.nav.wall'},         'url' => "{$oUserCurrent->getUserWebPath()}wall/", 'count' => $iCountWallUser ],
				[ 'name' => 'created',    'text' => {lang name='user.profile.nav.publications'}, 'url' => "{$oUserCurrent->getUserWebPath()}created/topics/", 'count' => $iCountCreated ],
				[ 'name' => 'favourites', 'text' => {lang name='user.profile.nav.favourite'},    'url' => "{$oUserCurrent->getUserWebPath()}favourites/topics/", 'count' => $iCountFavourite ],
				[ 'name' => 'friends',    'text' => {lang name='user.profile.nav.friends'},      'url' => "{$oUserCurrent->getUserWebPath()}friends/", 'count' => $iCountFriendsUser ],
				[ 'name' => 'activity',   'text' => {lang name='user.profile.nav.activity'},     'url' => "{$oUserCurrent->getUserWebPath()}stream/" ],
				[ 'name' => 'talk',       'text' => {lang name='user.profile.nav.messages'},     'url' => "{router page='talk'}", 'count' => $iUserCurrentCountTalkNew ],
				[ 'name' => 'settings',   'text' => {lang name='user.profile.nav.settings'},     'url' => "{router page='settings'}" ],
				[ 'name' => 'admin',      'text' => {lang name='admin.title'},                   'url' => "{router page='admin'}", 'is_enabled' => $oUserCurrent && $oUserCurrent->isAdministrator() ]
			]
		],
		[ 'text' => $aLang.block_create, 'url' => "{router page='content'}add/topic", 'attributes' => 'data-modal-target="modal-write"' ],
		[ 'text' => $aLang.talk.title,   'url' => "{router page='talk'}", 'title' => $aLang.talk.new_messages, 'is_enabled' => $iUserCurrentCountTalkNew, 'count' => $iUserCurrentCountTalkNew ],
		[ 'text' => $aLang.exit,         'url' => "{router page='login'}exit/?security_ls_key={$LIVESTREET_SECURITY_KEY}" ]
	]}
{else}
	{$aItems = [
		[ 'text' => $aLang.auth.login.title,        'classes' => 'js-modal-toggle-login',        'url' => {router page='login'} ],
		[ 'text' => $aLang.auth.registration.title, 'classes' => 'js-modal-toggle-registration', 'url' => {router page='registration'} ]
	]}
{/if}

{include 'components/nav/nav.tpl' sName='userbar' sActiveItem=$sMenuHeadItemSelect sMods='userbar' aItems=$aItems}