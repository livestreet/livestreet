{**
 * Приглашение на сайт
 *}

{extends 'Component@email.email'}

{block 'content'}
	{lang name='emails.invite.text' params=[
		'user_url'     => $oUserFrom->getUserWebPath(),
		'user_name'    => $oUserFrom->getDisplayName(),
		'website_url'  => Router::GetPath('/'),
		'website_name' => Config::Get('view.name'),
		'invite_code'  => $sRefCode,
		'ref_link'     => $sRefLink,
		'login_url'    => {router page='auth/login'}
	]}
{/block}