{**
 * Черновики
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
    {$bNoSidebar = true}
	{$sNav = 'create'}
{/block}

{block 'layout_page_title'}
	{$aLang.topic.add.title.add}
{/block}

{block 'layout_content'}
	{include 'components/topic/topic-list.tpl' topics=$aTopics paging=$aPaging}
{/block}