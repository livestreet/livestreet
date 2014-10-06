{**
 * Список городов в которых проживают пользователи
 *
 * @styles css/blocks.css
 *}

{extends 'components/block/block.tpl'}

{block 'block_title'}
	{lang 'user.blocks.cities.title'}
{/block}

{block 'block_options' append}
	{$mods = "{$mods} tags-city"}
{/block}

{block 'block_content'}
	{include 'components/tags/tag_cloud.tpl'
			 aTags     = $aCityList
			 sTagsUrl  = '{router page=\'people\'}city/{$oTag->getId()}/'
			 sTagsText = '{$oTag->getName()|escape}'}
{/block}