{**
 * Базовый шаблон настроек пользователя
 *}

{extends './layout.user.tpl'}

{block 'layout_options' append}
    {$sNav = 'settings'}
{/block}

{block 'layout_user_page_title'}
    {$aLang.user.settings.title}
{/block}