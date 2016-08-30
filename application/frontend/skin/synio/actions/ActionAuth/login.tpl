{**
 * Страница входа
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
    {$aLang.auth.login.title}
{/block}

{block 'layout_content'}
    {component 'auth' template='login' showExtra=true}
{/block}