{**
 * Активность пользователя
 *
 * @param array   $activityEvents
 * @param integer $activityEventsAllCount
 *}

{extends 'layouts/layout.user.tpl'}

{block 'layout_user_page_title'}
    {lang name='activity.title'}
{/block}

{block 'layout_content' append}
    {component 'activity'
        events   = $activityEvents
        count    = $activityEventsAllCount
        targetId = $oUserProfile->getId()
        classes  = 'js-activity--user'}
{/block}