{**
 * Навигация на странице активности
 *}

{include 'components/nav/nav.tpl'
		 sName       = 'activity'
		 sActiveItem = $sMenuItemSelect
		 sMods       = 'pills'
		 aItems = [
		   	[ 'name' => 'user', 'url' => "{router page='stream'}user/", 'text' => $aLang.stream_menu_user, 'is_enabled' => !! $oUserCurrent ],
		   	[ 'name' => 'all',  'url' => "{router page='stream'}all/",  'text' => $aLang.stream_menu_all ]
		 ]}