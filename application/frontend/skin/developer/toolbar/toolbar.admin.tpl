{**
 * Тулбар
 * Кнопка перехода в админку
 *
 * @styles css/toolbar.css
 * @scripts js/livestreet/toolbar.js
 *}

{extends 'components/toolbar/toolbar.item.tpl'}

{block 'toolbar_item_options' append}
	{$_sMods = 'admin'}
	{$_bShow = $oUserCurrent and $oUserCurrent->isAdministrator()}
{/block}

{block 'toolbar_item'}
	{toolbar_item_icon sUrl="{router page='admin'}" sTitle="{$aLang.admin_title}" sIcon="icon-cog"}
{/block}