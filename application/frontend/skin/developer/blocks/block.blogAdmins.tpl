{**
 * Список управляющих блога
 *
 * @styles css/blocks.css
 *}

{extends 'blocks/block.aside.base.tpl'}

{block 'block_title'}{$aLang.blog.administrators}{/block}
{block 'block_type'}blog-admins{/block}

{block 'block_content'}
	{* Создатель *}
	{include 'user_list_small.tpl' aUserList=[ $oBlog->getOwner() ] sUserListSmallTitle=$aLang.blog.owner}

	{* Администраторы *}
	{include 'user_list_small.tpl' aUserList=$aBlogAdministrators sUserListSmallTitle="{$aLang.blog.administrators} ({$iCountBlogAdministrators})"}

	{* Модераторы *}
	{include 'user_list_small.tpl' aUserList=$aBlogModerators sUserListSmallTitle="{$aLang.blog.moderators} ({$iCountBlogModerators})"}
{/block}