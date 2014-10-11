{**
 * Участники личного сообщения
 *}

{include 'components/user-list-add/user-list-add.tpl'
	sUserListAddClasses         = "message-users js-message-users"
	sUserListAddAttributes      = "data-param-i-target-id=\"{$oTalk->getId()}\""
	aUserList                   = $oTalk->getTalkUsers()
	allowManage                 = $oTalk->getUserId() == $oUserCurrent->getId() || $oUserCurrent->isAdministrator()
	sUserListTitle              = $aLang.talk.users.title
	aUserListSmallExcludeRemove = [ $oUserCurrent->getId() ]
	sUserItemInactiveTitle      = $aLang.talk.users.user_not_found
	sUserListSmallItemPath      = 'components/talk/talk-users-item.tpl'}