{**
 * Новый пароль
 *}

{extends 'components/email/email.tpl'}

{block 'content'}
	{lang name='emails.reminder_password.text' params=[
		'password' => $sNewPassword
	]}
{/block}