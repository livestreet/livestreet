{**
 * Вся активность
 *
 * @param array   $activityEvents
 * @param integer $activityEventsAllCount
 *}

{extends 'layouts/layout.activity.tpl'}

{block 'layout_content'}
    {component 'activity' events=$activityEvents count=$activityEventsAllCount classes='js-activity--all'}
{/block}