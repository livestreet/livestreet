{extends 'entry.base.tpl'}

{block 'entry_options'}
	{$oEntry = $oTalk}
	{$sEntryType = 'talk'}
{/block}

{block 'entry' append}
	{* Участники личного сообщения *}
	{if $oTalk->getUserId() == $oUserCurrent->getId() or $oUserCurrent->isAdministrator()}
		{include 'user_list_add.tpl'
				 sUserListType = 'message'
				 iUserListId = $oTalk->getId()
				 aUserList = $oTalk->getTalkUsers()
				 sUserListTitle = $aLang.talk_speaker_title
				 aUserListSmallExcludeRemove = [ $oUserCurrent->getId() ]
				 sUserItemInactiveTitle = $aLang.talk_speaker_not_found}
	{/if}
{/block}