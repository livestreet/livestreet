{**
 * Тулбар
 * Кнопка прокрутки страницы вверх
 *
 * @styles css/toolbar.css
 * @scripts js/livestreet/toolbar.js
 *}

{extends 'components/toolbar/toolbar.item.tpl'}

{block 'toolbar_item_options' append}
	{$_sMods = 'scrollup'}
	{$_sAttributes = 'id="toolbar_scrollup"'}
{/block}

{block 'toolbar_item'}
	{toolbar_item_icon sAttributes='onclick="return ls.toolbar.up.goUp();"' sTitle="{$aLang.toolbar_scrollup_go}" sIcon="icon-chevron-up"}
{/block}