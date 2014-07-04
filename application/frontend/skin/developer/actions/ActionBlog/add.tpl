{**
 * Создание блога
 * TODO: Вынести 'rangelength'  > в конфиг
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
	{$bNoSidebar = true}

	{if $sEvent == 'edit'}
		{$sNav = 'blog.edit'}
	{/if}
{/block}

{block 'layout_page_title'}
	{if $sEvent == 'add'}
		{$aLang.blog.add.title}
	{else}
		{$aLang.blog.admin.title}: <a href="{$oBlogEdit->getUrlFull()}">{$oBlogEdit->getTitle()|escape}</a>
	{/if}
{/block}

{block 'layout_content'}
	{include 'components/blog/add.tpl'}
{/block}