{**
 * Блок со списоком блогов
 * Список блогов
 *
 * TODO: Component item
 *}

<ul class="block-item-list">
    {foreach $aBlogs as $blog}
        <li>
            <a href="{$blog->getUrlFull()}">
                <img src="{$blog->getAvatarPath(48)}" alt="{$blog->getTitle()|escape}" class="avatar" />
            </a>

            {if $blog->getType() == 'close'}
                <i title="{lang 'blog.blocks.blogs.item.private'}" class="icon icon-lock"></i>
            {/if}

            <a href="{$blog->getUrlFull()}">{$blog->getTitle()|escape}</a>

            <p>{lang 'blog.users.readers_total'}: <strong>{$blog->getCountUser()}</strong></p>
            <p>{lang 'blog.topics_total'}: <strong>{$blog->getCountTopic()}</strong></p>
        </li>
    {/foreach}
</ul>