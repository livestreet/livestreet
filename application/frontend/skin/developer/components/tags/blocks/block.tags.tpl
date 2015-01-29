{**
 * Теги
 *
 * @styles css/blocks.css
 *}

{extends 'Component@block.block'}

{block 'block_title'}
	{lang 'tags.block_tags.title'}
{/block}

{block 'block_options' append}
	{$mods = "{$mods} tags nopadding"}
{/block}

{block 'block_content'}
	{component 'tags' template='cloud' tags=$smarty.local.tags url=$smarty.local.url}
{/block}