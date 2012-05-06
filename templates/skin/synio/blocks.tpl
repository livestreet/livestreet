{get_blocks assign='aBlocksLoad'}

{if isset($aBlocksLoad.$group)}
	{foreach from=$aBlocksLoad.$group item=aBlock}
		{if $aBlock.type=='block'}
			{insert name="block" block=$aBlock.name params=$aBlock.params}
		{/if}
		{if $aBlock.type=='template'}
			{include file=$aBlock.name params=$aBlock.params}
		{/if}
	{/foreach}
{/if}