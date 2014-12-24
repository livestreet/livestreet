{**
 * Форма восстановления пароля
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
    {$aLang.auth.reset.title}
{/block}

{block 'layout_content'}
    {include 'components/auth/auth.reset.tpl'}
{/block}