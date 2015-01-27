{**
 * Последняя активность
 * Топики отсортированные по времени последнего комментария
 *}

<div class="block-content">
    <ul class="block-item-list">
        {foreach $smarty.local.comments as $comment}
            {$user = $comment->getUser()}
            {$topic = $comment->getTarget()}
            {$blog = $topic->getBlog()}

            <li class="js-title-comment" title="{$comment->getText()|strip_tags|trim|truncate:100:'...'|escape}">
                <a href="{$user->getUserWebPath()}"><img src="{$user->getProfileAvatarPath(48)}" alt="avatar" class="avatar" /></a>

                <a href="{$user->getUserWebPath()}" class="author">{$user->getDisplayName()}</a> &rarr;
                <a href="{$blog->getUrlFull()}" class="blog-name">{$blog->getTitle()|escape}</a> &rarr;
                <a href="{if Config::Get('module.comment.nested_per_page')}{router page='comments'}{else}{$topic->getUrl()}#comment{/if}{$comment->getId()}">
                    {$topic->getTitle()|escape}
                </a>

                <p>
                    <time datetime="{date_format date=$comment->getDate() format='c'}">
                        {date_format date=$comment->getDate() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y"}
                    </time> |

                    {lang name='comments.comments_declension' count=$topic->getCountComment() plural=true}
                </p>
            </li>
        {/foreach}
    </ul>
</div>