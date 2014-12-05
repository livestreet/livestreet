{**
 * Список пользователей которые подключены к блогу
 *
 * @param object  $blog
 * @param array   $blogUsers
 * @param integer $countBlogUsers
 * @param array   $paging
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
    {$bNoSidebar = true}
{/block}

{block 'layout_page_title'}
    {$aLang.blog.users.readers_all} ({$countBlogUsers}):
    <a href="{$blog->getUrlFull()}">{$blog->getTitle()|escape}</a>
{/block}

{block 'layout_content'}
    {include 'components/user/user-list.tpl' users=$blogUsers pagination=$paging}
{/block}