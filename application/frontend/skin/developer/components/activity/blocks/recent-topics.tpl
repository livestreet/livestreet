{**
 * Последняя активность
 * Последние топики
 *}

<div class="block-content">
    <ul class="block-item-list">
        {foreach $smarty.local.topics as $topic}
            {$user = $topic->getUser()}
            {$blog = $topic->getBlog()}

            <li class="js-title-topic" title="{$topic->getText()|strip_tags|trim|truncate:150:'...'|escape}">
                <a href="{$user->getUserWebPath()}"><img src="{$user->getProfileAvatarPath(48)}" alt="avatar" class="avatar" /></a>

                <a href="{$user->getUserWebPath()}" class="author">{$user->getDisplayName()}</a> &rarr;
                <a href="{$blog->getUrlFull()}" class="blog-name">{$blog->getTitle()|escape}</a> &rarr;
                <a href="{$topic->getUrl()}">{$topic->getTitle()|escape}</a>

                <p>
                    <time datetime="{date_format date=$topic->getDate() format='c'}">
                        {date_format date=$topic->getDateAdd() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y"}
                    </time> |

                    {lang name='comments.comments_declension' count=$topic->getCountComment() plural=true}
                </p>
            </li>
        {/foreach}
    </ul>
</div>