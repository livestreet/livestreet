{**
 * Навигация на главной странице профиля
 *}

{include 'components/nav/nav.tpl'
		 sName          = 'profile_info'
		 sActiveItem    = $sMenuSubItemSelect
		 sMods          = 'pills'
		 aHookArguments = [ 'oUserProfile' => $oUserProfile ]
		 aItems         = [ [ 'text' => $aLang.user_menu_profile_whois, 'url' => $oUserProfile->getUserWebPath(), 'name' => 'main' ] ]}