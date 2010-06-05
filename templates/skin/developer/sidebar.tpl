<div id="sidebar">
	<div class="block">
		<form action="{router page='search'}topics/" method="GET">
			<input class="text" type="text" onblur="if (!value) value=defaultValue" onclick="if (value==defaultValue) value=''" value="{$aLang.search}" name="q" />
			<input class="button" type="submit" value="{$aLang.search_submit}" />
		</form>
	</div>

	{if isset($aBlocks.right)}
		{foreach from=$aBlocks.right item=aBlock}
			{if $aBlock.type=='block'}
				{insert name="block" block=`$aBlock.name` params=`$aBlock.params`}
			{/if}
			{if $aBlock.type=='template'}
				{include file=`$aBlock.name` params=`$aBlock.params`}
			{/if}
		{/foreach}
	{/if}
</div>