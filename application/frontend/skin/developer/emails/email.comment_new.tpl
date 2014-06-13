{**
 * Оповещение о новом комментарии в топике
 *}

{extends 'components/email/email.tpl'}

{block 'content'}
	{lang name='emails.comment_new.text' params=[
		'user_url'     => $oUserComment->getUserWebPath(),
		'user_name'    => $oUserComment->getDisplayName(),
		'topic_name'   => $oTopic->getTitle()|escape,
		'comment_url'  => "{if Config::Get('module.comment.nested_per_page')}{router page='comments'}{else}{$oTopic->getUrl()}#comment{/if}{$oComment->getId()}",
		'comment_text' => "{if Config::Get('sys.mail.include_comment')}{lang name='emails.common.comment_text'}:<br><em>{$oComment->getText()}</em>{/if}",
		'unsubscribe'  => "{if $sSubscribeKey}<br><br>{lang name='emails.comment_new.unsubscribe' unsubscribe_url="{router page='subscribe'}unsubscribe/{$sSubscribeKey}/"}{/if}"
	]}
{/block}