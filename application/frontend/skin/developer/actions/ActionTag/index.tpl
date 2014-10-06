{**
 * Поиск по тегам
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
	{lang 'tags.search.title'}
{/block}

{block 'layout_content'}
	{include 'components/tags/search_form.tags.tpl'}
	{include 'components/topic/topic-list.tpl' topics=$aTopics paging=$aPaging}
{/block}