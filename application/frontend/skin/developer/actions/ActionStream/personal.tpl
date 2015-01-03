{**
 * Настраиваемая, персональная страница активности
 *
 * @param array   $activityEvents
 * @param integer $activityEventsAllCount
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options' append}
    {$sNav = 'activity'}
{/block}

{block 'layout_page_title'}
    {$aLang.activity.title}
{/block}

{block 'layout_content'}
    {component 'activity' events=$activityEvents count=$activityEventsAllCount classes='js-activity--personal'}
{/block}