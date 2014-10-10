{**
 * Список пользователей блога
 *
 * @styles css/blocks.css
 *}

{extends 'components/block/block.tpl'}

{block 'block_type'}blog-actions{/block}

{block 'block_options' append}
	{$mods = "{$mods} blog-actions"}
{/block}

{block 'block_content'}
	<a href="{$oBlog->getUrlFull()}">
		<img src="{$oBlog->getAvatarPath(500)}" alt="{$oBlog->getTitle()|escape}" class="avatar" />
	</a>
{/block}

{block 'block_footer'}
	{* Подписаться через RSS *}
	{include 'components/button/button.tpl' url="{router page='rss'}blog/{$oBlog->getUrl()}/" text=$aLang.blog.rss}

	{* Вступить / Покинуть блог *}
	{include 'components/blog/join.tpl'}
{/block}