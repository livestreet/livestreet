{**
 * Поиск по тегам
 *
 * @param array  $topics
 * @param array  $paging
 * @param string $tag
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
    {lang 'tags.search.title'}
{/block}

{block 'layout_content'}
    {component 'tags' template='search-form'}
    {component 'topic' template='list' topics=$topics paging=$paging}
{/block}