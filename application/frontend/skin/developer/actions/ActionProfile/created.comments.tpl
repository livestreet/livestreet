{**
 * Список комментариев созданных пользователем
 *
 * @param array $comments
 * @param array $paging
 *}

{extends 'layouts/layout.user.tpl'}

{block 'layout_user_page_title'}
    {lang name='user.publications.title'}
{/block}

{block 'layout_content' append}
    {include 'navs/nav.user.created.tpl'}
    {component 'comment' template='list' comments=$comments paging=$paging classes='js-topic-comments-list'}
{/block}