{**
 * Управление пользователями блога
 *
 * @param object $blogEdit         Блог
 * @param array  $blogUsers        Список пользователей блога
 * @param array  $blogUsersInvited Список приглашенных пользователей, передается в случае если блог закрытый
 *}

{extends 'layouts/layout.blog.edit.tpl'}

{block 'layout_page_title'}
    {$aLang.blog.admin.title}: <a href="{$blogEdit->getUrlFull()}">{$blogEdit->getTitle()|escape}</a>
{/block}

{block 'layout_content'}
    {component 'blog' template='admin' users=$blogUsers pagination=$paging}
{/block}