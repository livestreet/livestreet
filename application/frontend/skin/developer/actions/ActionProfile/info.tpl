{**
 * Профиль пользователя с информацией о нем
 *
 * @styles css/profile.css
 * @styles css/tables.css
 *}

{extends 'layouts/layout.user.tpl'}

{block 'layout_options'}
	{$oSession = $oUserProfile->getSession()}
	{$oGeoTarget = $oUserProfile->getGeoTarget()}
{/block}

{block 'layout_user_page_title'}
	{lang name='user.profile.title'}
{/block}

{block 'layout_content' append}
	{include 'navs/nav.user.info.tpl'}

	{include 'components/user/info.tpl'}

	{**
	 * Стена
	 *}
	<h2 class="header-table mt-30">{lang name='wall.title'}</h2>

	{insert name='block' block='wall' params=[
		'classes' => 'js-wall-default',
		'user_id' => $oUserProfile->getId()
	]}

	{hook run='profile_whois_item_end' oUserProfile=$oUserProfile}
	{hook run='user_info_end' oUserProfile=$oUserProfile}
{/block}