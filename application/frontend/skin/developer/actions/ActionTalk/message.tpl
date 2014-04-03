{**
 * Личное сообщение
 *}

{extends file='layouts/layout.user.messages.tpl'}

{block name='layout_content'}
	{* Сообщение *}
	{include './message_entry.tpl'}

	{* Вывод комментариев к сообщению *}
	{if ! $bNoComments}
		{include 'comments/comment_tree.tpl'
				 iTargetId            = $oTalk->getId()
				 sTargetType          = 'talk'
				 iCountComment        = $oTalk->getCountComment()
				 sDateReadLast        = $oTalk->getTalkUser()->getDateLast()
				 sNoticeCommentAdd    = $aLang.topic_comment_add
				 bNoCommentFavourites = true}
	{else}
		{include file='alert.tpl' mAlerts=$aLang.talk_deleted sAlertStyle='empty'}
	{/if}
{/block}