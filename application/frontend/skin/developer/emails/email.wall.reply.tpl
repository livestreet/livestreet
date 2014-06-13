{**
 * Ответ на сообщение на стене
 *}

{extends 'components/email/email.tpl'}

{block 'content'}
	{lang name='emails.wall_reply.text' params=[
		'user_url'            => $oUser->getUserWebPath(),
		'user_name'           => $oUser->getDisplayName(),
		'wall_url'            => "{$oUserWall->getUserWebPath()}wall/",
		'message_parent_text' => $oWallParent->getText(),
		'message_text'        => $oWall->getText()
	]}
{/block}