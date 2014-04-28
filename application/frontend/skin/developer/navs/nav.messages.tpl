{**
 * Навигация на странице личных сообщений
 *}

{include 'components/nav/nav.tpl'
		 sName       = 'talk'
		 sActiveItem = $sMenuSubItemSelect
		 sMods       = 'pills'
		 aItems = [
		   	[ 'name' => 'inbox',      'url' => "{router page='talk'}",            'text' => $aLang.talk_menu_inbox ],
		   	[ 'name' => 'new',        'url' => "{router page='talk'}inbox/new/",  'text' => $aLang.talk_menu_inbox_new, 'count' => $iUserCurrentCountTalkNew, 'is_enabled' => $iUserCurrentCountTalkNew ],
		   	[ 'name' => 'add',        'url' => "{router page='talk'}add/",        'text' => $aLang.talk_menu_inbox_create ],
		   	[ 'name' => 'favourites', 'url' => "{router page='talk'}favourites/", 'text' => $aLang.talk_menu_inbox_favourites, 'count' => $iCountTalkFavourite ],
		   	[ 'name' => 'blacklist',  'url' => "{router page='talk'}blacklist/",  'text' => $aLang.talk_menu_inbox_blacklist ]
		 ]}