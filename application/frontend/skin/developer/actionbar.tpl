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
			{if $aActionbarItem['show']|default:true}
				<li class="actionbar-item">
					<a href="{if $aActionbarItem['url']}{$aActionbarItem['url']}{else}#{/if}" class="actionbar-item-link {$aActionbarItem['classes']}" {$aActionbarItem['attributes']}>
						{if $aActionbarItem['icon']}<i class="{$aActionbarItem['icon']}"></i>{/if}
						{$aActionbarItem['text']}
					</a>
				</li>
			{/if}
		{/foreach}
	</ul>
{/if}