{**
 * Список управляющих блога
 *}

{extends 'components/block/block.tpl'}

{block 'block_title'}
    {$aLang.blog.administrators}
{/block}

{block 'block_options' append}
    {$mods = "{$mods} blog-admins"}
{/block}

{block 'block_content'}
    {* Создатель *}
    {include 'components/user/user-list-small.tpl' users=[ $blog->getOwner() ] title=$aLang.blog.owner}

    {* Администраторы *}
    {if count($blogAdministrators)}
        {include 'components/user/user-list-small.tpl' users=$blogAdministrators title="{$aLang.blog.administrators} ({count($blogAdministrators)})"}
    {/if}

    {* Модераторы *}
    {if count($blogModerators)}
        {include 'components/user/user-list-small.tpl' users=$blogModerators title="{$aLang.blog.moderators} ({count($blogModerators)})"}
    {/if}
{/block}