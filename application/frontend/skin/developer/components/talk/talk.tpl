{**
 * Диалог
 *
 * @param object $talk
 * @param array  $comments
 *}

{$talk = $smarty.local.talk}

{* Первое сообщение *}
{include './talk-message-root.tpl' oEntry=$talk sEntryType='talk'}

{* Участники личного сообщения *}
{include './talk-users.tpl'}

{* Вывод комментариев к сообщению *}
{include 'components/comment/comments.tpl'
	sClasses          = 'js-comments-talk'
	iTargetId         = $talk->getId()
	sTargetType       = 'talk'
	aComments         = $smarty.local.comments
	iCountComment     = $talk->getCountComment()
	sDateReadLast     = $talk->getTalkUser()->getDateLast()
	bForbidAdd        = $bNoComments
	sNoticeCommentAdd = $aLang.topic_comment_add
	sNoticeNotAllow   = $aLang.talk.notices.deleted
	bShowFavourite    = false}