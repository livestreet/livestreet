{**
 * Список стран в которых проживают пользователи
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_title'}{$aLang.block_country_tags}{/block}

{block name='block_content'}
	{include file='tag_cloud.tpl' 
			 aTags     = $aCountryList 
			 sTagsUrl  = '{router page=\'people\'}country/{$oTag->getId()}/'
			 sTagsText = '{$oTag->getName()|escape}'}
{/block}