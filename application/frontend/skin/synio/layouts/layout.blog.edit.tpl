{**
 * Форма ред-ия блога
 *}

{extends './layout.base.tpl'}

{block 'layout_options' append}
    {if $sEvent != 'add'}
        {$layoutNav = [[
            hook       => 'blog_edit',
            activeItem => $sMenuItemSelect,
            items => [
                [ 'name' => 'profile', 'url' => "{router page='blog'}edit/{$blogEdit->getId()}/",  'text' => $aLang.blog.admin.nav.profile ],
                [ 'name' => 'admin',   'url' => "{router page='blog'}admin/{$blogEdit->getId()}/", 'text' => $aLang.blog.admin.nav.users ]
            ]
        ]]}
    {/if}
{/block}