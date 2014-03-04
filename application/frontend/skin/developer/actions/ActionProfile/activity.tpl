{**
 * Активность пользователя
 *}

{extends file='layouts/layout.user.tpl'}

{block name='layout_user_page_title'}{$aLang.user_menu_profile_stream}{/block}

{block name='layout_content'}
	{include 'actions/ActionStream/event_list.tpl' iLoadTargetId=$oUserProfile->getId() sActivityType='user'}
{/block}