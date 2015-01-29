{**
 * Новый пароль
 *}

{extends 'Component@email.email'}

{block 'content'}
	{lang name='emails.reminder_password.text' params=[
		'password' => $sNewPassword
	]}
{/block}