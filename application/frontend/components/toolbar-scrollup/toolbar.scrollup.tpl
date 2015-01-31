{**
 * Тулбар
 * Кнопка прокрутки страницы вверх
 *
 * @styles css/toolbar.css
 * @scripts js/livestreet/toolbar.js
 *}

{extends 'component@toolbar.toolbar.item'}

{block 'toolbar_item_options' append}
	{$_mods = 'scrollup'}
	{$_classes = 'js-toolbar-scrollup'}
	{$_attributes = [ 'id' => 'toolbar_scrollup' ]}
{/block}

{block 'toolbar_item'}
	{toolbar_item_icon title="{lang 'toolbar.scrollup.title'}" icon="icon-chevron-up"}
{/block}