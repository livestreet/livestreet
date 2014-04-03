<li class="actionbar-item">
	{block 'actionbar_item'}
		<{if $aActionbarItem['url']}a{else}button{/if} {if $aActionbarItem['url']}href="{$aActionbarItem['url']}"{/if}
				class="button actionbar-item-link {$aActionbarItem['classes']} {block 'actionbar_item_classes'}{/block}"
				{if ! $aActionbarItem['url']}type="button"{/if}
				{$aActionbarItem['attributes']} {block 'actionbar_item_attributes'}{/block}>

			{block 'actionbar_item_icon'}
				{if $aActionbarItem['icon']}
					<i class="{$aActionbarItem['icon']}"></i>
				{/if}
			{/block}

			{block 'actionbar_item_text'}{$aActionbarItem['text']}{/block}
		</{if $aActionbarItem['url']}a{else}button{/if}>
	{/block}
</li>