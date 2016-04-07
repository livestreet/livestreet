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

{* Участники личного сообщения *}
{capture 'talk_message_root_participants'}
    {component 'talk' template='participants'
        users         = $talk->getTalkUsers()
        classes       = 'message-users js-message-users'
        attributes    = [ 'data-param-target_id' => $talk->getId() ]
        editable      = $talk->getUserId() == $oUserCurrent->getId() || $oUserCurrent->isAdministrator()
        excludeRemove = [ $oUserCurrent->getId() ]}
{/capture}

{component 'details'
    classes = 'js-details-default ls-talk-participants-details'
    title   = "{lang 'talk.users.title'} ({count($talk->getTalkUsers())})"
    content = $smarty.capture.talk_message_root_participants}

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