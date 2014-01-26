{extends file='emails/email.base.tpl'}

{block name='content'}
	Пользователь «<a href="{$oUserFrom->getUserWebPath()}">{$oUserFrom->getDisplayName()}</a>» пожаловался на пользователя «<a href="{$oUserTarget->getUserWebPath()}">{$oUserTarget->getDisplayName()}</a>».
	<br>
	<br>
	<b>Причина:</b> {$oComplaint->getTypeTitle()}<br/>
	{if $oComplaint->getText()}
		<b>Подробнее:</b> {$oComplaint->getText()}<br/>
	{/if}
{/block}