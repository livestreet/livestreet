{**
 * Список топиков созданных пользователем
 *
 * @param array $topics
 * @param array $paging
 *}

{extends 'layouts/layout.user.created.tpl'}

{block 'layout_user_page_title'}
    {lang 'user.publications.title'}
{/block}

{block 'layout_content' append}
    {component 'topic.list' topics=$topics paging=$paging}
{/block}