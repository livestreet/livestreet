{**
 * Список пользователей блога
 *
 * @styles css/blocks.css
 *}

{extends 'blocks/block.aside.base.tpl'}

{block 'block_type'}blog-actions{/block}

{block 'block_content'}
	<a href="{$oBlog->getUrlFull()}">
		<img src="{$oBlog->getAvatarPath(500)}" alt="{$oBlog->getTitle()|escape}" class="avatar" />
	</a>
{/block}

{block 'block_footer'}
	{* Подписаться через RSS *}
	{include 'components/button/button.tpl' sUrl="{router page='rss'}blog/{$oBlog->getUrl()}/" sText=$aLang.blog.rss}

	{* Вступить / Покинуть блог *}
	{include 'components/blog/join.tpl'}
{/block}