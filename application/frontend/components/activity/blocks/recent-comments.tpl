{**
 * Последняя активность
 * Топики отсортированные по времени последнего комментария
 *}

{component_define_params params=[ 'comments' ]}

{capture 'items'}
    {foreach $comments as $comment}
        {$topic = $comment->getTarget()}

        {component 'activity' template='recent-item'
            user     = $comment->getUser()
            comment  = $comment
            topic    = $topic
            date     = $comment->getDate()
            classes = 'js-title-comment'
            attributes = [
                title => {$comment->getText()|strip_tags|trim|truncate:100:'...'|escape}
            ]}
    {foreachelse}
        {component 'blankslate' text={lang 'common.empty'} mods='no-background'}
    {/foreach}
{/capture}

{component 'item' template='group' items=$smarty.capture.items}