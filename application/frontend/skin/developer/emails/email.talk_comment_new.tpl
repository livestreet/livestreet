{**
 * Оповещение о новом сообщении в диалоге
 *}

{extends 'Component@email.email'}

{block 'content'}
	{lang name='emails.talk_comment_new.text' params=[
		'user_url'     => $oUserFrom->getUserWebPath(),
		'user_name'    => $oUserFrom->getDisplayName(),
		'talk_name'    => $oTalk->getTitle()|escape,
		'message_url'  => "{router page='talk'}read/{$oTalk->getId()}/#comment{$oTalkComment->getId()}",
		'message_text' => "{if Config::Get('sys.mail.include_comment')}{lang name='emails.common.comment_text'}:<br><em>{$oTalkComment->getText()}</em>{/if}"
	]}
{/block}