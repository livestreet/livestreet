{include file='header.tpl' menu='blog' showUpdateButton=true}


{include file='topic.tpl'}
{include 
	file='comment_tree.tpl' 	
	iTargetId=$oTopic->getId()
	sTargetType='topic'
	iCountComment=$oTopic->getCountComment()
	sDateReadLast=$oTopic->getDateRead()
	bAllowNewComment=$oTopic->getForbidComment()
	sNoticeNotAllow=$aLang.topic_comment_notallow
	sNoticeCommentAdd=$aLang.topic_comment_add
	aPagingCmt=$aPagingCmt}	


{include file='footer.tpl'}