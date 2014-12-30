{**
 * Плагины
 *
 * @param array $plugins Список плагинов
 *}

{extends 'layouts/layout.admin.tpl'}

{block 'layout_admin_page_title'}
    {lang 'admin.items.plugins'}
{/block}

{block 'layout_content'}
    {component 'admin' template='plugins' plugins=$plugins}
{/block}