{**
 * Навигация в профиле пользователя в разделе "Публикации"
 *}

{include 'components/nav/nav.tpl'
		 sName          = 'profile_created'
		 sActiveItem    = $sMenuSubItemSelect
		 sMods          = 'pills'
		 aHookArguments = [ 'oUserProfile' => $oUserProfile ]
		 aItems = [
		   	[ 'name' => 'topics',   'url' => "{$oUserProfile->getUserWebPath()}created/topics/",   'text' => $aLang.topic.topics, 'count' => $iCountTopicUser ],
		   	[ 'name' => 'comments', 'url' => "{$oUserProfile->getUserWebPath()}created/comments/", 'text' => $aLang.user_menu_publication_comment, 'count' => $iCountCommentUser ],
		   	[ 'name' => 'notes',    'url' => "{$oUserProfile->getUserWebPath()}created/notes/",    'text' => $aLang.user_menu_profile_notes, 'count' => $iCountNoteUser, 'is_enabled' => $oUserCurrent and $oUserCurrent->getId() == $oUserProfile->getId() ]
		 ]}