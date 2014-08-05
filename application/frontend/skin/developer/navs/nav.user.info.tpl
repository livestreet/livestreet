{**
 * Навигация на главной странице профиля
 *}

{include 'components/nav/nav.tpl'
		 sName          = 'profile_info'
		 sActiveItem    = $sMenuSubItemSelect
		 sMods          = 'pills'
		 aHookArguments = [ 'oUserProfile' => $oUserProfile ]
		 aItems         = [ [ 'text' => {lang name='user.profile.title'}, 'url' => $oUserProfile->getUserWebPath(), 'name' => 'main' ] ]}