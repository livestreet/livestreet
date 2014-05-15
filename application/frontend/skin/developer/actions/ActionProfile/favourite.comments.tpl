{**
 * Избранные комментарии пользователя
 *}

{extends 'layouts/layout.user.tpl'}

{block 'layout_user_page_title'}
	{$aLang.user_menu_profile_favourites}
{/block}

{block 'layout_content' append}
	{include 'navs/nav.user.favourite.tpl'}
	{include 'comments/comment_list.tpl'}
{/block}