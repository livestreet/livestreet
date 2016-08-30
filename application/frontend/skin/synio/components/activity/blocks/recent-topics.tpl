{**
 * Последняя активность
 * Последние топики
 *}

{component_define_params params=[ 'topics' ]}

{capture 'items'}
    {foreach $topics as $topic}
        {component 'activity' template='recent-item'
            user     = $topic->getUser()
            topic    = $topic
            date     = $topic->getDatePublish()}
    {foreachelse}
        {component 'blankslate' text={lang 'common.empty'} mods='no-background'}
    {/foreach}
{/capture}

{component 'item' template='group' items=$smarty.capture.items}