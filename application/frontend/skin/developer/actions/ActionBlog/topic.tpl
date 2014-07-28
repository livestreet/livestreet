{**
 * Топик
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_content'}
	{* Топик *}
	{include 'components/topic/topic.tpl' topic=$oTopic}

	{* Комментарии *}
	{include 'components/comment/comments.tpl'
			 sClasses          = 'js-comments-topic'
			 iTargetId         = $oTopic->getId()
			 iAuthorId         = $oTopic->getUserId()
			 aComments         = $aComments
			 sAuthorNotice     = $aLang.topic.author
			 sTargetType       = 'topic'
			 iCountComment     = $oTopic->getCountComment()
			 sDateReadLast     = $oTopic->getDateRead()
			 bForbidAdd        = $oTopic->getForbidComment()
			 sNoticeNotAllow   = $aLang.topic.comments.notices.not_allowed
			 bAllowSubscribe   = true
			 oSubscribeComment = $oTopic->getSubscribeNewComment()
			 aPagingCmt        = $aPagingCmt
			 bShowVote         = true
			 bShowFavourite    = true}
{/block}