{**
 * Вся активность
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$bNoSidebar = true}
	{$sNav = 'activity'}
{/block}

{block name='layout_page_title'}{$aLang.stream_menu}{/block}

{block name='layout_content'}
	{include file='actions/ActionStream/event_list.tpl' sActivityType='all'}
{/block}