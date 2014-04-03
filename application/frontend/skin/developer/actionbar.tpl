{**
 * Экшнбар
 *
 * @param array  $aActionbarItems  Массив
 *
 * @styles css/common.css
 *}

{if $aActionbarItems}
	<ul class="actionbar clearfix {$sActionbarClasses}" {$sActionbarAttributes}>
		{foreach $aActionbarItems as $aActionbarItem}
			{if $aActionbarItem['html']}
				{$aActionbarItem['html']}
			{else}
				{if $aActionbarItem['show']|default:true}
					{include 'actionbar.item.tpl'}
				{/if}
			{/if}
		{/foreach}
	</ul>
{/if}