{**
 * Блок с кнопкой добавления блога
 *}

{extends 'components/block/block.tpl'}

{block 'block_options' append}
    {$mods = "{$mods} blog-add"}

    {if ! $oUserCurrent}
        {$show = false}
    {/if}
{/block}

{block 'block_content'}
    {if $oUserCurrent && ($oUserCurrent->getRating() > {cfg name='acl.create.blog.rating'} or $oUserCurrent->isAdministrator())}
        <p>{$aLang.blog.can_add}</p>

        {include 'components/button/button.tpl' url="{router page='blog'}add/" mods='primary large' text=$aLang.blog.create_blog}
    {else}
        <p>{lang name='blog.cant_add' rating=Config::Get('acl.create.blog.rating')}</p>

        {include 'components/button/button.tpl' mods='primary large' text=$aLang.blog.create_blog isDisabled=true}
    {/if}
{/block}