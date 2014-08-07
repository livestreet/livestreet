{**
 * Настройка уведомлений
 *}

{extends 'layouts/layout.user.settings.tpl'}

{block 'layout_content' append}
	{include 'components/user/settings/tuning.tpl' user=$oUserCurrent}
{/block}