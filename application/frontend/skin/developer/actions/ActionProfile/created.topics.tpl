{**
 * Список топиков созданных пользователем
 *
 * @param array $topics
 * @param array $paging
 *}

{extends 'layouts/layout.user.tpl'}

{block 'layout_user_page_title'}
    {lang name='user.publications.title'}
{/block}

{block 'layout_content' append}
    {include 'navs/nav.user.created.tpl'}
    {component 'topic' template='list' topics=$topics paging=$paging}
{/block}