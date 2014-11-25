{**
 * Базовый шаблон блоков
 *}

{$component = 'block'}

{block 'block_options'}
	{$mods = $smarty.local.mods}
	{$classes = $smarty.local.classes}
	{$attributes = $smarty.local.attributes}
	{$show = $smarty.local.show|default:true}
{/block}

{if $show}
	{block 'block_before'}{/block}

	<div class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes}>
		{* Шапка *}
		{block 'block_title' hide}
			<header class="{$component}-header">
				<h3 class="{$component}-title">
					{$smarty.block.child}
				</h3>

				{block 'block_header_end'}{/block}
			</header>
		{/block}

		{block 'block_header_after'}{/block}

		{* Навигация *}
		{block 'block_nav' hide}
			<nav class="{$component}-nav">
				{$smarty.block.child}
			</nav>
		{/block}

		{block 'block_nav_after'}{/block}

		{* Содержимое *}
		{block 'block_content' hide}
			<div class="{$component}-content">
				{$smarty.block.child}
			</div>
		{/block}

		{block 'block_content_after'}{/block}

		{* Подвал *}
		{block 'block_footer' hide}
			<footer class="{$component}-footer">
				{$smarty.block.child}
			</footer>
		{/block}

		{block 'block_footer_after'}{/block}
	</div>

	{block 'block_after'}{/block}
{/if}