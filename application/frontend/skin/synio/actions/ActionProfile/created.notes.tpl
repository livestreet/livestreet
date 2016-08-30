{**
 * Список заметок созданных пользователем
 *
 * @param array $notesUsers
 * @param array $paging
 *}

{extends 'layouts/layout.user.created.tpl'}

{block 'layout_user_page_title'}
    {lang 'user.publications.title'}
{/block}

{block 'layout_content' append}
    {component 'user.list' users=$notesUsers pagination=$paging}
{/block}