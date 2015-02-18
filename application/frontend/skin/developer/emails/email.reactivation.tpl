{**
 * Повторная активация
 *}

{extends 'Component@email.email'}

{block 'content'}
	{lang name='emails.reactivation.text' params=[
		'website_url'    => Router::GetPath('/'),
		'website_name'   => Config::Get('view.name'),
		'activation_url' => "{router page='auth'}activate/{$oUser->getActivateKey()}/"
	]}
{/block}