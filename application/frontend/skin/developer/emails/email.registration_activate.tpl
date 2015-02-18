{**
 * Подтверждение регистрации
 *}

{extends 'Component@email.email'}

{block 'content'}
	{lang name='emails.registration_activate.text' params=[
		'website_url'    => Router::GetPath('/'),
		'website_name'   => Config::Get('view.name'),
		'user_name'      => $oUser->getLogin(),
		'user_password'  => $sPassword,
		'activation_url' => "{router page='auth'}activate/{$oUser->getActivateKey()}/"
	]}
{/block}