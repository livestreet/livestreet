{**
 * Последняя активность
 *}

{* Подвал *}
{capture 'block_footer'}
    <a href="{router page='rss'}allcomments/">{lang 'activity.block_recent.feed'}</a>
{/capture}

{component 'block'
    mods     = 'primary activity-recent'
    classes  = 'js-block-default'
    title    = {lang 'activity.block_recent.title'}
    titleUrl = {router 'stream'}
    footer   = $smarty.capture.block_footer
    tabs     = [
        'classes' => 'js-tabs-block js-activity-block-recent-tabs',
        'tabs' => [
            [ 'text' => {lang 'activity.block_recent.comments'}, 'url' => "{router page='ajax'}stream/comment", 'list' => $smarty.local.content ],
            [ 'text' => {lang 'activity.block_recent.topics'},   'url' => "{router page='ajax'}stream/topic" ]
        ]
    ]}