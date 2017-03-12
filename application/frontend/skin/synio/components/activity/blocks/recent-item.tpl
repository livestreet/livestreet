{component_define_params params=[ 'user', 'topic', 'date', 'classes', 'attributes' ]}

<div class="ls-activity-block-recent-item {$classes}" {cattr list=$attributes}>
    <a href="{$user->getUserWebPath()}" class="ls-activity-block-recent-user">{$user->getDisplayName()}</a>

    <time datetime="{date_format date=$date format='c'}" class="ls-activity-block-recent-date">
        {date_format date=$date hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y"}
    </time>

    <div>
        <a href="{$topic->getUrl()}" class="ls-activity-block-recent-title">{$topic->getTitle()|escape}</a>

        <a href="{$topic->getUrl()}#comments" class="ls-activity-block-recent-comments">
            <i class="ls-activity-block-recent-comments-icon"></i>
            {$topic->getCountComment()}
        </a>
    </div>
</div>