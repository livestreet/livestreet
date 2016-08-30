{**
 * Базовый шаблон админки
 *}

{extends './layout.base.tpl'}

{block 'layout_page_title'}
    <a href="{router page='admin'}">{lang 'admin.title'}</a>
    <span>&raquo;</span>
    {block 'layout_admin_page_title'}{/block}
{/block}