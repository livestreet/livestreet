{**
 * Диалог
 *
 * @param object  $talk
 * @param array   $comments
 * @param integer $lastCommentId
 *}

{extends 'layouts/layout.user.messages.tpl'}

{block 'layout_content'}
    {include 'components/talk/talk.tpl' talk=$talk comments=$comments lastCommentId=$lastCommentId}
{/block}