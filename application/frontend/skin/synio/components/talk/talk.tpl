{**
 * Диалог
 *
 * @param object $talk
 * @param array  $comments
 * @param array  $lastCommentId
 *}

{component_define_params params=[ 'talk', 'comments', 'lastCommentId' ]}

{* Первое сообщение *}
{component 'talk' template='message-root' talk=$talk}

{if $activeParticipantsCount || $comments}
    {* Вывод комментариев к сообщению *}
    {component 'comment' template='comments'
        comments      = $comments
        classes       = 'js-comments-talk'
        attributes    = [ 'id' => 'comments' ]
        targetId      = $talk->getId()
        targetType    = 'talk'
        count         = $talk->getCountComment()
        dateReadLast  = $talk->getTalkUser()->getDateLast()
        lastCommentId = $lastCommentId
        forbidText    = $aLang.talk.notices.deleted}
{/if}