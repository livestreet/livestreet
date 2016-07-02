{**
 * Список комментариев созданных пользователем
 *
 * @param array $comments
 * @param array $paging
 *}

{extends 'layouts/layout.user.created.tpl'}

{block 'layout_user_page_title'}
    {lang 'user.publications.title'}
{/block}

{block 'layout_content' append}
    {component 'comment.list' comments=$comments paging=$paging classes='js-topic-comments-list'}
{/block}