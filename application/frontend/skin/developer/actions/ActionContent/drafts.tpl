{**
 * Черновики
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
	{$sNav = 'create'}
{/block}

{block 'layout_page_title'}
	{$aLang.topic_create}
{/block}

{block 'layout_content'}
	{include 'components/topic/topic-list.tpl' topics=$aTopics paging=$aPaging}
{/block}