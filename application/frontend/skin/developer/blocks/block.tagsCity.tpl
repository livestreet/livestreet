{**
 * Список городов в которых проживают пользователи
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_title'}{$aLang.block_city_tags}{/block}

{block name='block_content'}
	{include 'components/tags/tag_cloud.tpl' 
			 aTags     = $aCityList 
			 sTagsUrl  = '{router page=\'people\'}city/{$oTag->getId()}/'
			 sTagsText = '{$oTag->getName()|escape}'}
{/block}