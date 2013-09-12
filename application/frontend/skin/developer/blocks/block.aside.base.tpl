{**
 * Базовый шаблон блоков
 *}

{block name='block_options'}{/block}

{if ! $bBlockNotShow}
	{block name='block_before'}{/block}

	<div class="block block-type-{block name='block_type'}default{/block} {block name='block_class'}{/block}" id="{block name='block_id'}{/block}" {block name='block_attributes'}{/block}>
		{* Header *}
		{block name='block_title' hide}
			<header class="block-header">
				<h3 class="block-title">{$smarty.block.child}</h3>

				{block name='block_header_end'}{/block}
			</header>
		{/block}
		
		{block name='block_header_after'}{/block}

		{* Navigation *}
		{block name='block_nav' hide}
			<nav class="block-nav">
				{$smarty.block.child}
			</nav>
		{/block}
		
		{block name='block_nav_after'}{/block}

		{* Content *}
		{block name='block_content' hide}
			<div class="block-content">
				{$smarty.block.child}
			</div>
		{/block}
		
		{block name='block_content_after'}{/block}

		{* Footer *}
		{block name='block_footer' hide}
			<footer class="block-footer">
				{$smarty.block.child}
			</footer>
		{/block}
		
		{block name='block_footer_after'}{/block}
	</div>

	{block name='block_after'}{/block}
{/if}