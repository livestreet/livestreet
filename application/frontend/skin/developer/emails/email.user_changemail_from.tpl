{**
 * Смена почты
 *}

{extends 'Component@email.email'}

{block 'content'}
	{lang name='emails.user_changemail.text' params=[
		'user_url'   => $oUser->getUserWebPath(),
		'user_name'  => $oUser->getDisplayName(),
		'mail_old'   => $oChangemail->getMailFrom(),
		'mail_new'   => $oChangemail->getMailTo(),
		'change_url' => "{router page='profile'}changemail/confirm-from/{$oChangemail->getCodeFrom()}/"
	]}
{/block}