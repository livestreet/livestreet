{**
 * Список комментариев созданных пользователем
 *}

{extends file='layout.base.tpl'}

{block name='layout_options'}
	{$sNav = 'people'}
{/block}

{block name='layout_content'}
	{include file='actions/ActionProfile/profile_top.tpl'}
	{include file='navs/nav.profile_created.tpl'}
	{include file='comments/comment_list.tpl'}
{/block}