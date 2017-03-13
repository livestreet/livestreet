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
            date     = $topic->getDatePublish()
            classes = 'js-title-topic'
            attributes = [
                title => {$topic->getText()|strip_tags|trim|truncate:150:'...'|escape}
            ]}
    {foreachelse}
        {component 'blankslate' text={lang 'common.empty'} mods='no-background'}
    {/foreach}
</div>