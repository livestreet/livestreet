{**
 * Настраиваемая, персональная страница активности
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
	{$sNav = 'activity'}
{/block}

{block 'layout_page_title'}
	{$aLang.activity.title}
{/block}

{block 'layout_content'}
	{include 'components/activity/activity.tpl' events=$activityEvents count=$activityEventsAllCount classes='js-activity--personal'}
{/block}