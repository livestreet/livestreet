{**
 * Активность пользователя
 *}

{extends 'layouts/layout.user.tpl'}

{block 'layout_user_page_title'}
	{$aLang.user_menu_profile_stream}
{/block}

{block 'layout_content' append}
	{include 'actions/ActionStream/event_list.tpl' iLoadTargetId=$oUserProfile->getId() sActivityType='user'}
{/block}