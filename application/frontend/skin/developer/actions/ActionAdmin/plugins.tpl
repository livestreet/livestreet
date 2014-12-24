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
    {include 'components/admin/plugins.tpl' plugins=$plugins}
{/block}