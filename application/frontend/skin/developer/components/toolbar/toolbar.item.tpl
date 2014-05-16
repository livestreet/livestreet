{* Название компонента *}
{$_sComponentName = 'toolbar-item'}

{block 'toolbar_item_options'}
	{$_sMods = ''}
	{$_sClasses = ''}
	{$_sAttributes = ''}
	{$_bShow = true}
{/block}

{if $_bShow}
	<section class="{$_sComponentName} {mod name=$_sComponentName mods=$_sMods} {$_sClasses}" {$_sAttributes}>
		{block 'toolbar_item'}{/block}
	</section>
{/if}