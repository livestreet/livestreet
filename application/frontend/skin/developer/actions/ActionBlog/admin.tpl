{**
 * Управление пользователями блога
 *
 * @param object oBlogEdit  Блог
 * @param array  aBlogUsers Список пользователей
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
	{if $oBlogEdit->getType() != 'close'}
		{$bNoSidebar = true}
	{/if}

	{$sNav = 'blog.edit'}
{/block}

{block 'layout_page_title'}
	{$aLang.blog.admin.title}: <a href="{$oBlogEdit->getUrlFull()}">{$oBlogEdit->getTitle()|escape}</a>
{/block}

{block 'layout_content'}
	{include 'components/blog/admin.tpl' users=$aBlogUsers pagination=$aPaging}
{/block}