{**
 * Оповещение о новом сообщении
 *}

{extends 'Component@email.email'}

{block 'content'}
	{lang name='emails.talk_new.text' params=[
		'user_url'  => $oUserFrom->getUserWebPath(),
		'user_name' => $oUserFrom->getDisplayName(),
		'talk_name' => $oTalk->getTitle()|escape,
		'talk_url'  => "{router page='talk'}read/{$oTalk->getId()}/",
		'talk_text' => "{if Config::Get('sys.mail.include_talk')}{lang name='emails.common.comment_text'}:<br><em>{$oTalk->getText()}</em>{/if}"
	]}
{/block}