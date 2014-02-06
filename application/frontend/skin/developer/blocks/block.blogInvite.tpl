{**
 * Приглашение пользователей в закрытый блог.
 * Выводится на странице администрирования пользователей закрытого блога.
 *
 * @styles css/blocks.css
 *}

{extends 'blocks/block.aside.base.tpl'}

{block 'block_title'}{$aLang.blog.invite.invite_users}{/block}
{block 'block_type'}blog-invite{/block}

{block 'block_content'}
	{include 'user_list_add.tpl'
			 sUserListType = 'blog_invite'
			 iUserListId = $oBlogEdit->getId()
			 aUserList = $aBlogUsersInvited
			 sUserListSmallItemPath = 'user_list_small_item.blog_invite.tpl'}
{/block}