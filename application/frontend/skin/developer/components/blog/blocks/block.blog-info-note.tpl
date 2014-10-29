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
	{lang 'topic.blocks.tip.title'}
{/block}

{block 'block_content'}
	{lang 'topic.blocks.tip.text'}
{/block}