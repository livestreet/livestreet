{**
 * Топик
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_content'}
	{* Топик *}
	{include 'topics/topic.tpl'}

	{* Комментарии *}
	{include 'components/comment/comments.tpl'
			 sClasses          = 'js-comments-topic'
			 iTargetId         = $oTopic->getId()
			 iAuthorId         = $oTopic->getUserId()
			 aComments         = $aComments
			 sAuthorNotice     = $aLang.topic_author
			 sTargetType       = 'topic'
			 iCountComment     = $oTopic->getCountComment()
			 sDateReadLast     = $oTopic->getDateRead()
			 bForbidAdd        = $oTopic->getForbidComment()
			 sNoticeNotAllow   = $aLang.topic_comment_notallow
			 sNoticeCommentAdd = $aLang.topic_comment_add
			 bAllowSubscribe   = true
			 oSubscribeComment = $oTopic->getSubscribeNewComment()
			 aPagingCmt        = $aPagingCmt
			 bShowVote         = true
			 bShowFavourite    = true}
{/block}