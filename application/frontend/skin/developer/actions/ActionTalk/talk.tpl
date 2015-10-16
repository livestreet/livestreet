{**
 * Диалог
 *
 * @param object  $talk
 * @param array   $comments
 * @param integer $lastCommentId
 *}

{extends 'layouts/layout.user.messages.tpl'}

{block 'layout_content'}
    {component 'talk'
        talk = $talk
        comments = $comments
        lastCommentId = $lastCommentId
        activeParticipantsCount = $activeParticipantsCount}
{/block}