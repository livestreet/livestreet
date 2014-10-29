{**
 * Теги
 *
 * @styles css/blocks.css
 *}

{extends 'components/block/block.tpl'}

{block 'block_title'}
	{lang 'tags.block_tags.title'}
{/block}

{block 'block_options' append}
	{$mods = "{$mods} tags nopadding"}
{/block}

{block 'block_content'}
	{include 'components/tags/tag-cloud.tpl' tags=$smarty.local.tags url=$smarty.local.url}
{/block}