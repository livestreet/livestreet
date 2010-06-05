{include file='header.tpl' menu='blog'}


{include file='topic.tpl' tSingle="true"}
{include 
	file='comment_tree.tpl' 	
	iTargetId=$oTopic->getId()
	sTargetType='topic'
	iCountComment=$oTopic->getCountComment()
	sDateReadLast=$oTopic->getDateRead()
	bAllowNewComment=$oTopic->getForbidComment()
	sNoticeNotAllow=$aLang.topic_comment_notallow
	sNoticeCommentAdd=$aLang.topic_comment_add
}	


{include file='footer.tpl'}