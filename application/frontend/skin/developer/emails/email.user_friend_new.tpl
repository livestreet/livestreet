{**
 * Заявка в друзья
 *}

{extends 'components/email/email.tpl'}

{block 'content'}
	{lang name='emails.user_friend_new.text' params=[
		'user_url'  => $oUserFrom->getUserWebPath(),
		'user_name' => $oUserFrom->getDisplayName(),
		'text'      => $sText,
		'url'       => $sPath
	]}
{/block}