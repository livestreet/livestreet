{**
 * Топик
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_content'}
    {* Топик *}
    {include 'components/topic/topic.tpl' topic=$oTopic}

    {* Комментарии *}
    {include 'components/comment/comments.tpl'
        comments      = $aComments
        count         = $oTopic->getCountComment()
        classes       = 'js-comments-topic'
        attributes    = [ 'id' => 'comments' ]
        targetId      = $oTopic->getId()
        targetType    = 'topic'
        authorId      = $oTopic->getUserId()
        authorText    = $aLang.topic.author
        dateReadLast  = $oTopic->getDateRead()
        forbidAdd     = $oTopic->getForbidComment()
        forbidText    = $aLang.topic.comments.notices.not_allowed
        useSubscribe  = true
        isSubscribed  = $oTopic->getSubscribeNewComment() && $oTopic->getSubscribeNewComment()->getStatus()
        lastCommentId = $iMaxIdComment
        pagination    = $aPagingCmt
        useVote       = true
        useFavourite  = true}
{/block}