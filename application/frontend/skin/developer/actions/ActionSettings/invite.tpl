{**
 * Управление инвайтами
 *}

{extends 'layouts/layout.user.settings.tpl'}

{block 'layout_content' append}
	{include 'components/user/settings/invite.tpl' user=$oUserCurrent}
{/block}