{**
 * Навигация на странице личных сообщений
 *}

{include 'components/nav/nav.tpl'
		 sName       = 'talk'
		 sActiveItem = $sMenuSubItemSelect
		 sMods       = 'pills'
		 aItems = [
		   	[ 'name' => 'inbox',      'url' => "{router page='talk'}",            'text' => $aLang.talk.nav.inbox ],
		   	[ 'name' => 'new',        'url' => "{router page='talk'}inbox/new/",  'text' => $aLang.talk.nav.new, 'count' => $iUserCurrentCountTalkNew, 'is_enabled' => $iUserCurrentCountTalkNew ],
		   	[ 'name' => 'add',        'url' => "{router page='talk'}add/",        'text' => $aLang.talk.nav.add ],
		   	[ 'name' => 'favourites', 'url' => "{router page='talk'}favourites/", 'text' => $aLang.talk.nav.favourites, 'count' => $iCountTalkFavourite ],
		   	[ 'name' => 'blacklist',  'url' => "{router page='talk'}blacklist/",  'text' => $aLang.talk.nav.blacklist ]
		 ]}