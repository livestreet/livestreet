{**
 * Блок с кнопкой добавления блога
 *}

{if $oUserCurrent}
    {capture 'block_content'}
        {if $oUserCurrent && ($oUserCurrent->getRating() > Config::Get('acl.create.blog.rating') or $oUserCurrent->isAdministrator())}
            <p>{$aLang.blog.can_add}</p>

            {component 'button' url="{router page='blog'}add/" mods='primary large' text=$aLang.blog.create_blog}
        {else}
            <p>{lang name='blog.cant_add' rating=Config::Get('acl.create.blog.rating')}</p>

            {component 'button' mods='primary large' text=$aLang.blog.create_blog isDisabled=true}
        {/if}
    {/capture}

    {component 'block' mods='blog-add' content=$smarty.capture.block_content}
{/if}