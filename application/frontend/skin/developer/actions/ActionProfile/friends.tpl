{**
 * Список друзей
 *
 * @param array $friends
 * @param array $paging
 *}

{extends 'layouts/layout.user.tpl'}

{block 'layout_user_page_title'}
    {lang name='user.friends.title'}
{/block}

{block 'layout_content' append}
    {component 'user' template='list' users=$friends pagination=$paging}
{/block}