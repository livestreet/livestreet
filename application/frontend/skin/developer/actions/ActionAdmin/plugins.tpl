{**
 * Плагины
 *
 * @param array $plugins Список плагинов
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
    {$bNoSidebar = true}
{/block}

{block 'layout_page_title'}
    <a href="{router page='admin'}">{lang name='admin.title'}</a>
    <span>&raquo;</span>
    {lang name='admin.items.plugins'}
{/block}

{block 'layout_content'}
    {include 'components/admin/plugins.tpl' plugins=$plugins}
{/block}