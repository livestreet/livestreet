{include file='header.tpl' menu='blog'}
{assign var="oUser" value=$oTopic->getUser()}

{include file='topic.tpl'}
{include 
	file='comment_tree.tpl' 	
	iTargetId=$oTopic->getId()
	iAuthorId=$oUser->getId()
	sTargetType='topic'
	iCountComment=$oTopic->getCountComment()
	sDateReadLast=$oTopic->getDateRead()
	bAllowNewComment=$oTopic->getForbidComment()
	sNoticeNotAllow=$aLang.topic_comment_notallow
	sNoticeCommentAdd=$aLang.topic_comment_add
	bAllowSubscribe=true
	oSubscribeComment=$oTopic->getSubscribeNewComment()
	aPagingCmt=$aPagingCmt}


{include file='footer.tpl'}