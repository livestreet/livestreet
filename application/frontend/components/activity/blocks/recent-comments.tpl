{**
 * Последняя активность
 * Топики отсортированные по времени последнего комментария
 *}

{capture 'items'}
    {foreach $smarty.local.comments as $comment}
        {$topic = $comment->getTarget()}

        {component 'activity' template='recent-item'
            user     = $comment->getUser()
            topic    = $topic
            blog     = $topic->getBlog()
            date     = $comment->getDate()
            topicUrl = ( Config::Get('module.comment.use_nested') ) ? {router 'comments'} : "{$topic->getUrl()}#comment{$comment->getId()}"}
    {foreachelse}
        {component 'blankslate' text={lang 'common.empty'} mods='no-background'}
    {/foreach}
{/capture}

{component 'item' template='group' items=$smarty.capture.items}