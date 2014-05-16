{**
 * Тулбар
 *
 * @styles css/toolbar.css
 * @scripts js/ui/toolbar.js
 * @scripts js/livestreet/toolbar.js
 *}

{* Название компонента *}
{$_sComponentName = 'toolbar'}

{function toolbar_item_icon}
	<a href="{$sUrl|default:'#'}" class="toolbar-item-link {$sClasses}" {$sAttributes}>
		<i class="{$sIcon}"></i>
	</a>
{/function}

<aside class="{$_sComponentName} {mod name=$_sComponentName mods=$smarty.local.sMods} {$smarty.local.sClasses} js-toolbar" {$smarty.local.sAttributes}>
	{include 'blocks.tpl' group='toolbar'}
</aside>