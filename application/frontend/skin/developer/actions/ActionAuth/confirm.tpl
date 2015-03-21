{**
 * Просьба перейти по ссылке отправленной на емэйл для активации аккаунта
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
    {$aLang.auth.registration.confirm.title}
{/block}

{block 'layout_content'}
    {$aLang.auth.registration.confirm.text}<br /><br />

    <a href="{router page='/'}">{$aLang.common.site_go_main}</a>
{/block}