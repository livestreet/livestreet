{**
 * Поиск по тегам
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
	{$aLang.block_tags_search}
{/block}

{block 'layout_content'}
	{include 'forms/form.search.tags.tpl'}
	{include 'topics/topic_list.tpl'}
{/block}