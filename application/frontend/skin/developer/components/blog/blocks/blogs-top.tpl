{**
 * Блок со списоком блогов
 * Список блогов
 *
 * TODO: Component item
 *}

<ul class="block-item-list">
    {foreach $aBlogs as $oBlog}
        <li>
            <a href="{$oBlog->getUrlFull()}">
                <img src="{$oBlog->getAvatarPath(48)}" alt="{$oBlog->getTitle()|escape}" class="avatar" />
            </a>

            {if $oBlog->getType() == 'close'}
                <i title="{lang 'blog.blocks.blogs.item.private'}" class="icon icon-lock"></i>
            {/if}

            <a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape}</a>

            <p>{lang 'blog.users.readers_total'}: <strong>{$oBlog->getCountUser()}</strong></p>
            <p>{lang 'blog.topics_total'}: <strong>{$oBlog->getCountTopic()}</strong></p>
        </li>
    {/foreach}
</ul>