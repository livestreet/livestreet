{**
 * Повторная активация
 *}

{extends 'components/email/email.tpl'}

{block 'content'}
	{lang name='emails.reactivation.text' params=[
		'website_url'    => Router::GetPath('/'),
		'website_name'   => Config::Get('view.name'),
		'activation_url' => "{router page='registration'}activate/{$oUser->getActivateKey()}/"
	]}
{/block}