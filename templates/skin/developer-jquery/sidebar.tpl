<aside id="sidebar" {if $sidebarPosition == 'left'}class="sidebar-left"{/if}>
	{if isset($aBlocks.right)}
		{foreach from=$aBlocks.right item=aBlock}
			{if $aBlock.type=='block'}
				{insert name="block" block=$aBlock.name params=$aBlock.params}
			{/if}
			{if $aBlock.type=='template'}
				{include file=$aBlock.name params=$aBlock.params}
			{/if}
		{/foreach}
	{/if}
</aside>