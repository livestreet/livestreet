{**
 * Регистрация через инвайт
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
    {$aLang.auth.invite.title}
{/block}

{block 'layout_content'}
    {component 'auth' template='invite'}
{/block}