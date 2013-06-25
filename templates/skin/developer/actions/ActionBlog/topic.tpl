{**
 * Топик
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_content'}
	{include file='topics/topic.tpl'}
	{include 
		file='comments/comment_tree.tpl' 	
		iTargetId=$oTopic->getId()
		iAuthorId=$oTopic->getUserId()
		sAuthorNotice=$aLang.topic_author
		sTargetType='topic'
		iCountComment=$oTopic->getCountComment()
		sDateReadLast=$oTopic->getDateRead()
		bAllowNewComment=$oTopic->getForbidComment()
		sNoticeNotAllow=$aLang.topic_comment_notallow
		sNoticeCommentAdd=$aLang.topic_comment_add
		bAllowSubscribe=true
		oSubscribeComment=$oTopic->getSubscribeNewComment()
		aPagingCmt=$aPagingCmt}
{/block}