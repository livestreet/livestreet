<li class="actionbar-item">
	{block 'actionbar_item'}
		{include 'components/button/button.tpl'
			sUrl        = $aItem['url']
			sClasses    = "actionbar-item-link {$aItem['classes']}"
			sText       = $aItem['text']
			sIcon       = $aItem['icon']
			sAttributes = $aItem['attributes']}
	{/block}
</li>