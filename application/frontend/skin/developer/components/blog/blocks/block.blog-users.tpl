{**
 * Список пользователей блога
 *}

{extends 'components/block/block.tpl'}

{block 'block_title'}
    {$iUsersCount = count($aBlogUsers)}

    <a href="{$oBlog->getUrlFull()}users/">
        {if $iUsersCount}
            {$iUsersCount} {$iUsersCount|declension:$aLang.blog.readers_declension:'russian'}
        {else}
            {$aLang.blog.users.empty}
        {/if}
    </a>
{/block}

{block 'block_options' append}
    {$mods = "{$mods} blog-users"}
{/block}

{block 'block_content'}
    {include 'components/user/user-list-avatar.tpl' aUsersList=$aBlogUsers}
{/block}