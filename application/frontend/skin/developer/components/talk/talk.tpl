{**
 * Диалог
 *
 * @param object $talk
 * @param array  $comments
 *}

{$talk = $smarty.local.talk}

{* Первое сообщение *}
{include './talk-message-root.tpl' talk=$talk}

{if ! $bNoComments}
    {* Участники личного сообщения *}
    {include './talk-users.tpl'}

    {* Вывод комментариев к сообщению *}
    {include 'components/comment/comments.tpl'
        comments     = $smarty.local.comments
        classes      = 'js-comments-talk'
        attributes   = [ 'id' => 'comments' ]
        targetId     = $talk->getId()
        targetType   = 'talk'
        count        = $talk->getCountComment()
        dateReadLast = $talk->getTalkUser()->getDateLast()
        forbidText   = $aLang.talk.notices.deleted}
{/if}