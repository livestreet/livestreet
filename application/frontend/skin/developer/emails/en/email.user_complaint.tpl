{extends file='emails/email.base.tpl'}

{block name='content'}
	User «<a href="{$oUserFrom->getUserWebPath()}">{$oUserFrom->getDisplayName()}</a>» complain on the user «<a href="{$oUserTarget->getUserWebPath()}">{$oUserTarget->getDisplayName()}</a>».
	<br>
	<br>
	<b>Reason:</b> {$oComplaint->getTypeTitle()}<br/>
	{if $oComplaint->getText()}
		<b>Details:</b> {$oComplaint->getText()}<br/>
	{/if}
{/block}