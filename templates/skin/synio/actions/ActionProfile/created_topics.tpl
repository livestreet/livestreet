{**
 * Список топиков созданных пользователем
 *}

{extends file='layout.user.tpl'}

{block name='layout_content'}
	{include file='navs/nav.profile_created.tpl'}
	{include file='topics/topic_list.tpl'}
{/block}