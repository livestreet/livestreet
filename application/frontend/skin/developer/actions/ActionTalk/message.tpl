{**
 * Личное сообщение
 *}

{extends file='layouts/layout.user.messages.tpl'}

{block name='layout_content'}
	{* Сообщение *}
	{include 'actions/ActionTalk/message_entry.tpl'}

	{* Вывод комментариев к сообщению *}
	{$oTalkUser = $oTalk->getTalkUser()}

	{if ! $bNoComments}
		{include 'comments/comment_tree.tpl'
				 iTargetId            = $oTalk->getId()
				 sTargetType          = 'talk'
				 iCountComment        = $oTalk->getCountComment()
				 sDateReadLast        = $oTalkUser->getDateLast()
				 sNoticeCommentAdd    = $aLang.topic_comment_add
				 bNoCommentFavourites = true}
	{else}
		{include file='alert.tpl' mAlerts=$aLang.talk_deleted sAlertStyle='empty'}
	{/if}
{/block}