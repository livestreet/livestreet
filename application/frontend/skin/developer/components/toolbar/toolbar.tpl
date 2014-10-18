{**
 * Тулбар
 *
 * @styles css/toolbar.css
 * @scripts js/ui/toolbar.js
 * @scripts js/livestreet/toolbar.js
 *}

{* Название компонента *}
{$component = 'toolbar'}

{function toolbar_item_icon}
	<{if $sUrl}a href="{$sUrl}"{else}div{/if} class="toolbar-item-button {$classes}" {$attributes} {if $sTitle}title="{$sTitle}"{/if}>
		<i class="{$sIcon}"></i>
	</{if $sUrl}a{else}div{/if}>
{/function}

<aside class="{$component} {mod name=$component mods=$smarty.local.mods} {$smarty.local.classes} js-toolbar" {$smarty.local.attributes}>
	{include 'blocks.tpl' group='toolbar'}
</aside>