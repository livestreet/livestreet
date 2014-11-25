{**
 * Смена пароля
 *}

{extends 'components/email/email.tpl'}

{block 'content'}
	{lang name='emails.reminder_code.text' params=[
		'website_url'  => Router::GetPath('/'),
		'website_name' => Config::Get('view.name'),
		'recover_url'  => "{router page='login'}reset/{$oReminder->getCode()}/"
	]}
{/block}