{**
 * Уведомления о смене емэйла
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$bNoSidebar = true}
	{$bNoSystemMessages = true}
{/block}

{block name='layout_content'}
	{$sText}
{/block}