{**
 * Базовый шаблон личных сообщений
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options' append}
	{$sNavContent = 'messages'}
{/block}

{block name='layout_page_title'}{$aLang.talk_menu_inbox}{/block}