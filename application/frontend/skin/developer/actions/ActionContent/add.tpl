{**
 * Создание топика
 *
 * @styles css/topic.css
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
	{if $sEvent == 'add'}
		{$sNav = 'create'}
	{/if}
{/block}

{block 'layout_page_title'}
	{if $sEvent == 'add'}
		{$aLang.topic.add.title.add}
	{else}
		{$aLang.topic.add.title.edit}
	{/if}
{/block}

{block 'layout_content'}
	{include 'components/topic/topic-add.tpl' topic=$oTopicEdit type=$oTopicType blogs=$aBlogsAllow}
{/block}