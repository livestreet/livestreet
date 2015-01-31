{**
 * Диалог
 *
 * @param object $talk
 * @param array  $comments
 * @param array  $lastCommentId
 *}

{$talk = $smarty.local.talk}

{* Первое сообщение *}
{include './talk-message-root.tpl' talk=$talk}

{if ! $bNoComments}
    {* Участники личного сообщения *}
    {include './participants/participants.tpl'
        users         = $talk->getTalkUsers()
        classes       = 'message-users js-message-users'
        attributes    = [ 'data-param-target_id' => $talk->getId() ]
        editable      = $talk->getUserId() == $oUserCurrent->getId() || $oUserCurrent->isAdministrator()
        title         = $aLang.talk.users.title
        excludeRemove = [ $oUserCurrent->getId() ]}

    {* Вывод комментариев к сообщению *}
    {component 'comment' template='comments'
        comments      = $smarty.local.comments
        classes       = 'js-comments-talk'
        attributes    = [ 'id' => 'comments' ]
        targetId      = $talk->getId()
        targetType    = 'talk'
        count         = $talk->getCountComment()
        dateReadLast  = $talk->getTalkUser()->getDateLast()
        lastCommentId = $smarty.local.lastCommentId
        forbidText    = $aLang.talk.notices.deleted}
{/if}