{component_define_params params=[ 'user', 'topic', 'date' ]}

{capture 'item_content'}
    <a href="{$user->getUserWebPath()}" class="ls-activity-block-recent-user">{$user->getDisplayName()}</a> &rarr;
    <a href="{$topic->getUrl()}">{$topic->getTitle()|escape}</a>

    <p class="ls-activity-block-recent-info">
        <time datetime="{date_format date=$date format='c'}" class="ls-activity-block-recent-time">
            {date_format date=$date hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y"}
        </time>

        <a href="{$topic->getUrl()}#comments" class="ls-activity-block-recent-comments">
            {component 'icon' icon='comments'}
            {lang 'comments.comments_declension' count=$topic->getCountComment() plural=true}
        </a>
    </p>
{/capture}

{component 'item'
    element = 'li'
    mods = 'image-rounded'
    desc = $smarty.capture.item_content
    image=[
        'path' => $user->getProfileAvatarPath(48),
        'url' => $user->getUserWebPath()
    ]
    params=$params}