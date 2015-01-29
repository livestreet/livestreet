{**
 * Список пользователей блога
 *}

{extends 'Component@block.block'}

{block 'block_title'}
    {$usersCount = count($blogUsers)}

    <a href="{$blog->getUrlFull()}users/">
        {if $usersCount}
            {$usersCount} {$usersCount|declension:$aLang.blog.readers_declension:'russian'}
        {else}
            {$aLang.blog.users.empty}
        {/if}
    </a>
{/block}

{block 'block_options' append}
    {$mods = "{$mods} blog-users"}
{/block}

{block 'block_content'}
    {component 'user' template='list-avatar' users=$blogUsers}
{/block}