{**
 * Список комментариев к записи на стене
 *
 * @param array $comments Список комментариев
 *}

{foreach $smarty.local.comments as $comment}
    {include './wall.entry.tpl' entry=$comment showReply=false classes='wall-comment js-wall-comment' type='comment'}
{/foreach}