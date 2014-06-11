{**
 * Диалог
 *}

{extends 'layouts/layout.user.messages.tpl'}

{block 'layout_content'}
	{* Первое сообщение *}
	{include './talk-message-root.tpl'}

	{* Вывод комментариев к сообщению *}
	{include 'components/comment/comment-list.tpl'
		sClasses          = 'js-comments-talk'
		iTargetId         = $oTalk->getId()
		sTargetType       = 'talk'
		aComments         = $aComments
		iCountComment     = $oTalk->getCountComment()
		sDateReadLast     = $oTalk->getTalkUser()->getDateLast()
		sNoticeCommentAdd = $aLang.topic_comment_add
		bShowFavourite    = false}
{/block}