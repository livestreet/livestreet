{**
 * Список пользователей блога
 *}

{capture 'block_title'}
    {$usersCount = count($blogUsers)}

    {if $usersCount}
        {$usersCount} {lang "{$smarty.local.titleLang|default:'blog.readers_declension'}" count=$usersCount plural=true}
    {else}
        {$aLang.blog.users.empty}
    {/if}
{/capture}

{component 'block'
    mods     = 'blog-users'
    title    = $smarty.capture.block_title
    titleUrl = "{$blog->getUrlFull()}users/"
    content  = {component 'user' template='list-avatar' users=$blogUsers}}