{**
 * Список пользователей блога
 *
 * @styles css/blocks.css
 *}

{extends 'blocks/block.aside.base.tpl'}

{block 'block_type'}blog-actions{/block}

{block 'block_content'}
	<a href="{$oBlog->getUrlFull()}">
		{* TODO: Fix avatar size *}
		<img src="{$oBlog->getAvatarPath(100)}" alt="{$oBlog->getTitle()|escape}" class="avatar" />
	</a>
{/block}

{block 'block_footer'}
	{* Подписаться через RSS *}
	<a href="{router page='rss'}blog/{$oBlog->getUrl()}/" class="button">RSS</a>

	{* Вступить / Покинуть блог *}
	{include 'actions/ActionBlog/button_join.tpl'}
{/block}