<li class="actionbar-item">
	{block 'actionbar_item'}
		<{if $aItem['url']}a{else}button{/if} {if $aItem['url']}href="{$aItem['url']}"{/if}
				class="button actionbar-item-link {$aItem['classes']} {block 'actionbar_item_classes'}{/block}"
				{if ! $aItem['url']}type="button"{/if}
				{$aItem['attributes']} {block 'actionbar_item_attributes'}{/block}>

			{block 'actionbar_item_icon'}
				{if $aItem['icon']}
					<i class="{$aItem['icon']}"></i>
				{/if}
			{/block}

			{block 'actionbar_item_text'}{$aItem['text']}{/block}
		</{if $aItem['url']}a{else}button{/if}>
	{/block}
</li>