{**
 * Выбор пользователей для чтения в ленте
 *
 * @styles css/blocks.css
 *}

{extends 'blocks/block.aside.base.tpl'}

{block 'block_title'}{$aLang.userfeed_block_users_title}{/block}
{block 'block_type'}activity{/block}

{block 'block_content'}
	{include 'user_list_add.tpl'
			 iUserListId = $oUserCurrent->getId()
			 sUserListType = 'userfeed'
			 aUserList = $aUserfeedSubscribedUsers
			 sUserListNote = $aLang.userfeed_settings_note_follow_user}
{/block}