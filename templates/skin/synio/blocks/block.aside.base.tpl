{**
 * Базовый шаблон блоков
 *
 * Доступные опции:
 *     noBlockHeader (bool)  - Не выводить шапку блока
 *     noBlockNav (bool)     - Не выводить навигацию
 *     noBlockContent (bool) - Не выводить контент
 *     noBlockFooter (bool)  - Не выводить подвал блока
 *}

{block name='block_options'}{/block}


<div class="block block-type-{block name='block_type'}default{/block} {block name='block_class'}{/block}" id="{block name='block_id'}{/block}" {block name='block_attributes'}{/block}>
	{* Header *}
	{if !$noBlockHeader}
		<header class="block-header">
			<h3 class="block-title">{block name='block_title'}No title{/block}</h3>

			{block name='block_header_end'}{/block}
		</header>
	{/if}
	
	{block name='block_header_after'}{/block}

	{* Navigation *}
	{if !$noBlockNav}
		<nav class="block-nav">
			{block name='block_nav'}No nav{/block}
		</nav>
	{/if}
	
	{block name='block_nav_after'}{/block}

	{* Content *}
	{if !$noBlockContent}
		<div class="block-content">
			{block name='block_content'}No content{/block}
		</div>
	{/if}
	
	{block name='block_content_after'}{/block}

	{* Footer *}
	{if !$noBlockFooter}
		<footer class="block-footer">
			{block name='block_footer'}No footer{/block}
		</footer>
	{/if}
	
	{block name='block_footer_after'}{/block}
</div>