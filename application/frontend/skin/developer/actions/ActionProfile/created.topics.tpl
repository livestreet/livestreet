{**
 * Список топиков созданных пользователем
 *}

{extends 'layouts/layout.user.tpl'}

{block 'layout_user_page_title'}
	{lang name='user.publications.title'}
{/block}

{block 'layout_content' append}
	{include 'navs/nav.user.created.tpl'}
	{include 'components/topic/topic-list.tpl' topics=$aTopics paging=$aPaging}
{/block}