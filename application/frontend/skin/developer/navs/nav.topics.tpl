{**
 * Навигация по топикам
 *}

{include 'components/nav/nav.tpl'
		 sName       = 'topics'
		 sActiveItem = $sMenuItemSelect
		 sMods    = 'pills'
		 aItems = [
		   	[ 'name' => 'index', 'url' => {router page='/'},    'text' => {lang name='blog.menu.all'}, 'count' => $iCountTopicsNew ],
		   	[ 'name' => 'feed',  'url' => {router page='feed'}, 'text' => $aLang.userfeed_title, 'is_enabled' => !! $oUserCurrent ]
		 ]}

{include file='navs/nav.topics.sub.tpl'}