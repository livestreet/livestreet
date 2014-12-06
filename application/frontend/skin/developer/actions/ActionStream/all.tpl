{**
 * Вся активность
 *
 * @param array   $activityEvents
 * @param integer $activityEventsAllCount
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options' append}
    {$layoutShowSidebar = false}
    {$sNav = 'activity'}
{/block}

{block 'layout_page_title'}
    {$aLang.activity.title}
{/block}

{block 'layout_content'}
    {include 'components/activity/activity.tpl' events=$activityEvents count=$activityEventsAllCount classes='js-activity--all'}
{/block}