{**
 * Форма запроса повторной активации аккаунта
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
    {$aLang.auth.reactivation.title}
{/block}

{block 'layout_content'}
    {component 'auth' template='reactivation'}
{/block}