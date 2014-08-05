{**
 * Активность пользователя
 *}

{extends 'layouts/layout.user.tpl'}

{block 'layout_user_page_title'}
	{lang name='activity.title'}
{/block}

{block 'layout_content' append}
	{include 'components/activity/activity.tpl'
		events   = $activityEvents
		count    = $activityEventsAllCount
		targetId = $oUserProfile->getId()
		classes  = 'js-activity--user'}
{/block}