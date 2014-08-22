{**
 * Список стран в которых проживают пользователи
 *
 * @styles css/blocks.css
 *}

{extends 'components/block/block.tpl'}

{block 'block_title'}
	{$aLang.block_country_tags}
{/block}

{block 'block_options' append}
	{$mods = "{$mods} tags-country"}
{/block}

{block 'block_content'}
	{include 'components/tags/tag_cloud.tpl'
			 aTags     = $aCountryList
			 sTagsUrl  = '{router page=\'people\'}country/{$oTag->getId()}/'
			 sTagsText = '{$oTag->getName()|escape}'}
{/block}