{**
 * Блок с информацией
 *}

{$component = 'user-info-group'}

{hook run="{$component}-{$smarty.local.name}-before"}

{* Получаем пункты установленные плагинами *}
{hook run="{$component}-{$smarty.local.name}-items" assign='itemsHook' items=$smarty.local.items array=true}
{$items = $itemsHook|default:$smarty.local.items}

{if $smarty.local.html || $smarty.local.items}
	<div class="{$component} {cmods name=$component mods=$smarty.local.mods} {$smarty.local.classes}" {cattr list=$smarty.local.attributes}>
		<h3 class="user-info-group-title">
			{$smarty.local.title}
		</h3>

		<div class="user-info-group-content">
			{if $smarty.local.html}
				{$smarty.local.html}
			{else}
				{component 'info-list' list=$smarty.local.items classes='user-info-group-items'}
			{/if}
		</div>
	</div>
{/if}

{hook run="{$component}-{$smarty.local.name}-after"}