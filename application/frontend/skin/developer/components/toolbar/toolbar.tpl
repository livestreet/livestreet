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
	<{if $url}a href="{$url}"{else}div{/if} class="toolbar-item-button {$classes}" {cattr list=$attributes} {if $title}title="{$title}"{/if}>
		<i class="{$icon}"></i>
	</{if $url}a{else}div{/if}>
{/function}

<aside class="{$component} {cmods name=$component mods=$smarty.local.mods} {$smarty.local.classes} js-toolbar" {cattr list=$smarty.local.attributes}>
	{include 'blocks.tpl' group='toolbar'}
</aside>