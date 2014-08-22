{**
 * Приглашение пользователей в закрытый блог.
 * Выводится на странице администрирования пользователей закрытого блога.
 *
 * @styles css/blocks.css
 *}

{extends 'components/block/block.tpl'}

{block 'block_title'}
	{$aLang.blog.invite.invite_users}
{/block}

{block 'block_options' append}
	{$mods = "{$mods} blog-invite"}
{/block}

{block 'block_content'}
	{include 'components/user_list_add/user_list_add.tpl'
			 sUserListAddClasses = "js-user-list-add-blog-invite"
			 sUserListAddAttributes = "data-param-i-target-id=\"{$oBlogEdit->getId()}\""
			 aUserList = $aBlogUsersInvited
			 sUserListSmallItemPath = 'components/user_list_small/user_list_small_item.blog_invite.tpl'}
{/block}