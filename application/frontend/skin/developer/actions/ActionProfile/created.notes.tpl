{**
 * Список заметок созданных пользователем
 *
 * @param array $notesUsers
 * @param array $paging
 *}

{extends 'layouts/layout.user.tpl'}

{block 'layout_user_page_title'}
    {lang 'user.publications.title'}
{/block}

{block 'layout_content' append}
    {include 'navs/nav.user.created.tpl'}
    {component 'user' template='list' users=$notesUsers pagination=$paging}
{/block}