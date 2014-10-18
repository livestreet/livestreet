{**
 * Блок с информацией
 *}

{$component = 'user-info-group'}

{hook run="{$component}-{$smarty.local.name}-before"}

{* Получаем пункты установленные плагинами *}
{hook run="{$component}-{$smarty.local.name}-items" assign='itemsHook' items=$smarty.local.items array=true}
{$items = $itemsHook|default:$smarty.local.items}

{if $smarty.local.html || $smarty.local.items}
	<div class="{$component} {mod name=$component mods=$smarty.local.mods} {$smarty.local.classes}" {$smarty.local.attributes}>
		<h3 class="user-info-group-title">
			{$smarty.local.title}
		</h3>

		<div class="user-info-group-content">
			{if $smarty.local.html}
				{$smarty.local.html}
			{else}
				{include 'components/info-list/info-list.tpl' aInfoList=$smarty.local.items classes='user-info-group-items'}
			{/if}
		</div>
	</div>
{/if}

{hook run="{$component}-{$smarty.local.name}-after"}