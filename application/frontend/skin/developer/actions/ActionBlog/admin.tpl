{**
 * Управление пользователями блога
 *
 * @param object $blogEdit         Блог
 * @param array  $blogUsers        Список пользователей блога
 * @param array  $blogUsersInvited Список приглашенных пользователей, передается в случае если блог закрытый
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options' append}
    {* Показываем сайдбар только для закрытых блогов *}
    {if $blogEdit->getType() != 'close'}
        {$layoutShowSidebar = false}
    {/if}

    {$sNav = 'blog.edit'}
{/block}

{block 'layout_page_title'}
    {$aLang.blog.admin.title}: <a href="{$blogEdit->getUrlFull()}">{$blogEdit->getTitle()|escape}</a>
{/block}

{block 'layout_content'}
    {include 'components/blog/admin.tpl' users=$blogUsers pagination=$paging}
{/block}