{**
 * Список топиков только из коллективных блогов
 *}

{extends file='layout.base.tpl'}

{block name='layout_options'}
	{$sNav = 'blog'}
	{$sNavContent = 'blog'}
{/block}

{block name='layout_content'}
	{include file='topics/topic_list.tpl'}
{/block}