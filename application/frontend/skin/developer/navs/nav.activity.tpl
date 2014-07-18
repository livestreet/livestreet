{**
 * Навигация на странице активности
 *}

{include 'components/nav/nav.tpl'
		 sName       = 'activity'
		 sActiveItem = $sMenuItemSelect
		 sMods       = 'pills'
		 aItems = [
		   	[ 'name' => 'user', 'url' => "{router page='stream'}personal/", 'text' => $aLang.activity.nav.personal, 'is_enabled' => !! $oUserCurrent ],
		   	[ 'name' => 'all',  'url' => "{router page='stream'}all/",  'text' => $aLang.activity.nav.all ]
		 ]}