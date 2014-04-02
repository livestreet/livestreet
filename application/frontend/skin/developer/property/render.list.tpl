{if $aPropertyItems}
<div class="property-list">
	{foreach $aPropertyItems as $oPropertyItem}
		{include 'property/render.item.tpl'}
	{/foreach}
</div>
{/if}