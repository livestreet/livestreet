{**
 * Список комментариев к записи на стене
 *
 * @param array $comments Список комментариев
 *}

{component_define_params params=[ 'comments' ]}

{foreach $comments as $comment}
    {component 'wall' template='entry' entry=$comment showReply=false classes='wall-comment js-wall-comment' type='comment'}
{/foreach}