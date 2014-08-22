{**
 * Подсказка отображаемая при создании топика
 *
 * @styles css/blocks.css
 *}

{extends 'components/block/block.tpl'}

{block 'block_options' append}
	{$mods = "{$mods} blog-info-note"}
{/block}

{block 'block_title'}
	{$aLang.block_blog_info_note}
{/block}

{block 'block_content'}
	{$aLang.block_blog_info_note_text}
{/block}