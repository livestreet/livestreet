{**
 * Список топиков из персональных блогов
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$sNav = 'blog'}
	{$sNavContent = 'blog'}
{/block}

{block name='layout_content'}
	{include file='topics/topic_list.tpl'}
{/block}