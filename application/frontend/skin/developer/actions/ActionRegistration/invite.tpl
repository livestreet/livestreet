{**
 * Регистрация через инвайт
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options' append}
    {$layoutShowSidebar = false}
{/block}

{block 'layout_page_title'}
    {$aLang.auth.invite.title}
{/block}

{block 'layout_content'}
    {include 'components/auth/auth.invite.tpl'}
{/block}