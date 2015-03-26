{**
 * Последняя активность
 * Последние топики
 *}

{capture 'items'}
    {foreach $smarty.local.topics as $topic}
        {include './recent-item.tpl'
            user     = $topic->getUser()
            topic    = $topic
            blog     = $topic->getBlog()
            date     = $topic->getDateAdd()
            topicUrl = $topic->getUrl()}
    {foreachelse}
        {component 'alert' mods='empty' text={lang 'common.empty'}}
    {/foreach}
{/capture}

{component 'item' template='group' items=$smarty.capture.items}