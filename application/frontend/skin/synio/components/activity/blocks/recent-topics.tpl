{**
 * Последняя активность
 * Последние топики
 *}

{component_define_params params=[ 'topics' ]}

<div class="ls-activity-block-recent-items">
    {foreach $topics as $topic}
        {component 'activity' template='recent-item'
            user     = $topic->getUser()
            topic    = $topic
            date     = $topic->getDatePublish()}
    {foreachelse}
        {component 'blankslate' text={lang 'common.empty'} mods='no-background'}
    {/foreach}
</div>