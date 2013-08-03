{extends file='emails/email.base.tpl'}

{block name='content'}
	Пользователь <a href="{$oUserTopic->getUserWebPath()}">{$oUserTopic->getLogin()}</a> опубликовал в блоге <b>«{$oBlog->getTitle()|escape:'html'}»</b> новый топик &mdash; <a href="{$oTopic->getUrl()}">{$oTopic->getTitle()|escape:'html'}</a>
{/block}