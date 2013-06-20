{**
 * Активность пользователя
 *}

{extends file='layout.base.tpl'}

{block name='layout_content'}
	{include file='actions/ActionProfile/profile_top.tpl'}

	<h3 class="profile-page-header">{$aLang.user_menu_profile_stream}</h3>

	{include file='actions/ActionStream/event_list.tpl' sActivityType='user' sActivityParams="data-param-i-user-id=\"{$oUserProfile->getId()}\""}
{/block}