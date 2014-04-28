{**
 * Выбор пользователей для чтения в ленте
 *
 * @styles css/blocks.css
 *}

{extends 'blocks/block.aside.base.tpl'}

{block 'block_title'}{$aLang.userfeed_block_users_title}{/block}
{block 'block_type'}activity{/block}

{block 'block_content'}
	{include 'components/user_list_add/user_list_add.tpl'
			 sUserListAddClasses = 'js-user-list-add-userfeed'
			 aUserList = $aUserfeedSubscribedUsers
			 sUserListAddAttributes = 'data-param-type="users"'
			 sUserListNote = $aLang.userfeed_settings_note_follow_user}
{/block}