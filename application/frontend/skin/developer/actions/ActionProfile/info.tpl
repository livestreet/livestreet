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
{/block}