{**
 * Блок со списоком блогов
 * Список блогов
 *}

{$items = []}

{foreach $aBlogs as $blog}
    {capture 'item_content'}
        {lang 'blog.users.readers_total'}: <strong>{$blog->getCountUser()}</strong><br>
        {lang 'blog.topics_total'}: <strong>{$blog->getCountTopic()}</strong>
    {/capture}

    {$items[] = [
        'title' => $blog->getTitle()|escape,
        'titleUrl' => $blog->getUrlFull(),
        'mods' => $blog->getUrlFull(),
        'content' => $smarty.capture.item_content,
        'image' => [
            'path' => $blog->getAvatarPath(48),
            'url' => $blog->getUrlFull(),
            'alt' => $blog->getTitle()|escape
        ]
    ]}
{/foreach}

{component 'item' template='group' items=$items}