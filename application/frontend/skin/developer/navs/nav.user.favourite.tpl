{**
 * Навигация в профиле пользователя в разделе "Избранное"
 *}

{include 'components/nav/nav.tpl'
		 sName          = 'profile_favourite'
		 sActiveItem    = $sMenuSubItemSelect
		 sMods          = 'pills'
		 aHookArguments = [ 'oUserProfile' => $oUserProfile ]
		 aItems = [
		   	[ 'name' => 'topics',   'text' => $aLang.user_menu_profile_favourites_topics,   'url'  => "{$oUserProfile->getUserWebPath()}favourites/topics/",   'count' => $iCountTopicFavourite ],
		   	[ 'name' => 'comments', 'text' => $aLang.user_menu_profile_favourites_comments, 'url'  => "{$oUserProfile->getUserWebPath()}favourites/comments/", 'count' => $iCountCommentFavourite ]
		 ]}