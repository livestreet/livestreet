{capture 'item_content'}
    <a href="{$user->getUserWebPath()}" class="author">{$user->getDisplayName()}</a> &rarr;
    <a href="{$blog->getUrlFull()}" class="blog-name">{$blog->getTitle()|escape}</a> &rarr;
    <a href="{$smarty.local.topicUrl}">{$topic->getTitle()|escape}</a>

    <p>
        <time datetime="{date_format date=$smarty.local.date format='c'}">
            {date_format date=$smarty.local.date hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y"}
        </time> |

        {lang 'comments.comments_declension' count=$topic->getCountComment() plural=true}
    </p>
{/capture}

{component 'item'
    element = 'li'
    mods = 'image-rounded'
    desc = $smarty.capture.item_content
    image=[
        'path' => $user->getProfileAvatarPath(48),
        'url' => $user->getUserWebPath()
    ]}