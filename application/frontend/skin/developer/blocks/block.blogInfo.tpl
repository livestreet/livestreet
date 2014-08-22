{**
 * Информация о блоге показываемая при создании топика
 *
 * @styles css/blocks.css
 *}

{extends 'components/block/block.tpl'}

{block 'block_title'}
	{$aLang.block_blog_info}
{/block}

{block 'block_options' append}
	{$mods = "{$mods} blog-info"}
{/block}

{block 'block_content'}
	<p class="text js-blog-info"></p>
{/block}