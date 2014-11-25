{* Название компонента *}
{$component = 'toolbar-item'}

{block 'toolbar_item_options'}
	{$_mods = ''}
	{$_classes = ''}
	{$_attributes = []}
	{$_bShow = true}
{/block}

{if $_bShow}
	<section class="{$component} {cmods name=$component mods=$_mods} {$_classes}" {cattr list=$_attributes}>
		{block 'toolbar_item'}{/block}
	</section>
{/if}