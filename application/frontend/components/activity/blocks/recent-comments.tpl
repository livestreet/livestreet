{**
 * Последняя активность
 * Топики отсортированные по времени последнего комментария
 *}

{capture 'items'}
    {foreach $smarty.local.comments as $comment}
        {$topic = $comment->getTarget()}

        {include './recent-item.tpl'
            user     = $comment->getUser()
            topic    = $topic
            blog     = $topic->getBlog()
            date     = $comment->getDate()
            topicUrl = ( Config::Get('module.comment.use_nested') ) ? {router 'comments'} : "{$topic->getUrl()}#comment{$comment->getId()}"}
    {foreachelse}
        {component 'alert' mods='empty' text={lang 'common.empty'}}
    {/foreach}
{/capture}

{component 'item' template='group' items=$smarty.capture.items}