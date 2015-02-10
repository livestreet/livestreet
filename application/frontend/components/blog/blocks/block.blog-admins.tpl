{**
 * Список управляющих блога
 *}

{capture 'block_content'}
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
{/capture}

{component 'block'
    mods     = 'blog-admins'
    title    = {lang 'blog.administrators'}
    content  = $smarty.capture.block_content}