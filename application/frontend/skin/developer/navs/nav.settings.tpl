{**
 * Навигация на странице настроек
 *}

{include 'components/nav/nav.tpl'
		 sName       = 'settings'
		 sActiveItem = $sMenuSubItemSelect
		 sMods       = 'pills'
		 aItems = [
		   	[ 'url' => "{router page='settings'}profile/", 'text' => $aLang.settings_menu_profile, 'name' => 'profile' ],
		   	[ 'url' => "{router page='settings'}account/", 'text' => $aLang.settings_menu_account, 'name' => 'account' ],
		   	[ 'url' => "{router page='settings'}tuning/",  'text' => $aLang.settings_menu_tuning,  'name' => 'tuning' ],
		   	[ 'url' => "{router page='settings'}invite/",  'text' => $aLang.settings_menu_invite,  'name' => 'invite', 'is_enabled' => $oConfig->GetValue('general.reg.invite') ]
		 ]}