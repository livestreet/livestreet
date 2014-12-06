{**
 * Форма запроса повторной активации аккаунта
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options' append}
    {$layoutShowSidebar = false}
{/block}

{block 'layout_page_title'}
    {$aLang.auth.reactivation.title}
{/block}

{block 'layout_content'}
    {include 'components/auth/auth.reactivation.tpl'}
{/block}