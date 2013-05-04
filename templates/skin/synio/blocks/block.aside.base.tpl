{**
 * Базовый шаблон блоков
 *
 * Доступные опции:
 *     noHeader (bool)  - Не выводить шапку блока
 *     noNav (bool)     - Не выводить навигацию
 *     noContent (bool) - Не выводить контент
 *     noFooter (bool)  - Не выводить подвал блока
 *}

{block name='options'}{/block}


<div class="block block-type-{block name='type'}default{/block} {block name='class'}{/block}" id="{block name='id'}{/block}" {block name='attributes'}{/block}>
	{* Header *}
	{if !$noHeader}
		<header class="block-header">
			<h3 class="block-title">{block name='title'}No title{/block}</h3>

			{block name='header_end'}{/block}
		</header>
	{/if}
	
	{block name='header_after'}{/block}

	{* Navigation *}
	{if !$noNav}
		<nav class="block-nav">
			{block name='nav'}No nav{/block}
		</nav>
	{/if}
	
	{block name='nav_after'}{/block}

	{* Content *}
	{if !$noContent}
		<div class="block-content">
			{block name='content'}No content{/block}
		</div>
	{/if}
	
	{block name='content_after'}{/block}

	{* Footer *}
	{if !$noFooter}
		<footer class="block-footer">
			{block name='footer'}No footer{/block}
		</footer>
	{/if}
	
	{block name='footer_after'}{/block}
</div>