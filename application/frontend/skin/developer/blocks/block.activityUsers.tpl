{**
 * Выбор пользователей для чтения в ленте активности
 *
 * @styles css/blocks.css
 *}

{extends 'blocks/block.aside.base.tpl'}

{block 'block_title'}{$aLang.stream_block_users_title}{/block}
{block 'block_type'}activity{/block}

{block 'block_content'}
	{include 'components/user_list_add/user_list_add.tpl'
			 sUserListAddClasses = 'js-user-list-add-activity'
			 aUserList = $aStreamSubscribedUsers
			 sUserListNote = $aLang.stream_settings_note_follow_user}
{/block}