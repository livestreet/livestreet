{**
 * Активность пользователя
 *}

{extends file='layouts/layout.user.tpl'}

{block name='layout_user_page_title'}{$aLang.user_menu_profile_stream}{/block}

{block name='layout_content'}
	{include file='actions/ActionStream/event_list.tpl' sActivityType='user' sActivityParams="data-param-i-user-id=\"{$oUserProfile->getId()}\""}
{/block}