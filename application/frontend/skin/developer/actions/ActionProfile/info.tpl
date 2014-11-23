{**
 * Профиль пользователя с информацией о нем
 *}

{extends 'layouts/layout.user.tpl'}

{block 'layout_options'}
	{$oSession = $oUserProfile->getSession()}
	{$oGeoTarget = $oUserProfile->getGeoTarget()}
{/block}

{block 'layout_content' append}
	{*include 'navs/nav.user.info.tpl'*}
	{include 'components/user/info.tpl'}
{/block}