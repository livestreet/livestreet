{**
 * Уведомление об успешной регистрации
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
    {$aLang.auth.registration.notices.success_activate}
{/block}

{block 'layout_content'}
    <a href="{router page='/'}">{$aLang.common.site_go_main}</a>
{/block}