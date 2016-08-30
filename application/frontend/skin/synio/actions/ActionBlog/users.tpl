{**
 * Список пользователей которые подключены к блогу
 *
 * @param object  $blog
 * @param array   $blogUsers
 * @param integer $countBlogUsers
 * @param array   $paging
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
    {$aLang.blog.users.readers_all} ({$countBlogUsers}):
    <a href="{$blog->getUrlFull()}">{$blog->getTitle()|escape}</a>
{/block}

{block 'layout_content'}
    {component 'user' template='list' users=$blogUsers pagination=$paging}
{/block}