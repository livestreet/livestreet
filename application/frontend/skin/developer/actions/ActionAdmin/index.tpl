{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
    {$bNoSidebar = true}
{/block}

{block 'layout_page_title'}
    {lang name='admin.title'}
{/block}

{block 'layout_content'}
    {include 'components/admin/manage.tpl'}
{/block}