{extends 'entry.base.tpl'}

{block 'entry_options'}
	{$oEntry = $oTalk}
	{$sEntryType = 'talk'}
{/block}

{block 'entry' append}
	{* Участники личного сообщения *}
	{if $oTalk->getUserId() == $oUserCurrent->getId() or $oUserCurrent->isAdministrator()}
		{include 'user_list_add.tpl'
				 sUserListAddClasses = "message-users js-message-users"
				 sUserListAddAttributes = "data-param-i-target-id=\"{$oTalk->getId()}\""
				 aUserList = $oTalk->getTalkUsers()
				 sUserListTitle = $aLang.talk_speaker_title
				 aUserListSmallExcludeRemove = [ $oUserCurrent->getId() ]
				 sUserItemInactiveTitle = $aLang.talk_speaker_not_found
			 	 sUserListSmallItemPath = 'user_list_small_item.message.tpl'}
	{/if}
{/block}