{**
 * Топик
 *
 * @param object  $topic
 * @param array   $comments
 * @param integer $lastCommentId
 * @param array   $pagingComments
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options' append}
    {$layouShowSidebar = Config::Get( 'view.topic_show_sidebar' )}
{/block}

{block 'layout_content'}
    {* Топик *}
    {include 'components/topic/topic.tpl' topic=$topic}

    {* Комментарии *}
    {include 'components/comment/comments.tpl'
        comments      = $comments
        count         = $topic->getCountComment()
        classes       = 'js-comments-topic'
        attributes    = [ 'id' => 'comments' ]
        targetId      = $topic->getId()
        targetType    = 'topic'
        authorId      = $topic->getUserId()
        authorText    = $aLang.topic.author
        dateReadLast  = $topic->getDateRead()
        forbidAdd     = $topic->getForbidComment()
        forbidText    = $aLang.topic.comments.notices.not_allowed
        useSubscribe  = true
        isSubscribed  = $topic->getSubscribeNewComment() && $topic->getSubscribeNewComment()->getStatus()
        lastCommentId = $lastCommentId
        pagination    = $pagingComments
        useVote       = true
        useFavourite  = true}
{/block}