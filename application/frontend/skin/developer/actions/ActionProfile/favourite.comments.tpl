{**
 * Избранные комментарии пользователя
 *}

{extends 'layouts/layout.user.tpl'}

{block 'layout_user_page_title'}
	{lang name='user.favourites.title'}
{/block}

{block 'layout_content' append}
	{include 'navs/nav.user.favourite.tpl'}
	{include 'components/comment/comment-list.tpl' aComments=$aComments}
{/block}