{**
 * Список управляющих блога
 *}

{extends 'Component@block.block'}

{block 'block_title'}
    {$aLang.blog.administrators}
{/block}

{block 'block_options' append}
    {$mods = "{$mods} blog-admins"}
{/block}

{block 'block_content'}
    {* Создатель *}
    {component 'user' template='list-small' users=[ $blog->getOwner() ] title=$aLang.blog.owner}

    {* Администраторы *}
    {if count($blogAdministrators)}
        {component 'user' template='list-small' users=$blogAdministrators title="{$aLang.blog.administrators} ({count($blogAdministrators)})"}
    {/if}

    {* Модераторы *}
    {if count($blogModerators)}
        {component 'user' template='list-small' users=$blogModerators title="{$aLang.blog.moderators} ({count($blogModerators)})"}
    {/if}
{/block}