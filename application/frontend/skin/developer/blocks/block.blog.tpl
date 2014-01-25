{**
 * Краткая информация о блоге на странице топика
 *
 * @styles css/blocks.css
 *}

{extends 'blocks/block.aside.base.tpl'}

{block 'block_options'}
	{$oBlog = $oTopic->getBlog()}
{/block}

{if $oTopic && $oBlog->getType() != 'personal'}
	{block 'block_title'}<a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape}</a>{/block}
	{block 'block_class'}block-type-blog{/block}

	{block 'block_content'}
		<span id="blog_user_count_{$oBlog->getId()}">{$oBlog->getCountUser()}</span>
		{$oBlog->getCountUser()|declension:$aLang.blog.readers_declension}<br />
		{$oBlog->getCountTopic()} {$oBlog->getCountTopic()|declension:$aLang.topic_declension}

		<br />
		<br />

		{* Подписаться через RSS *}
		<a href="{router page='rss'}blog/{$oBlog->getUrl()}/" class="button">RSS</a>

		{* Вступить / Покинуть блог *}
		{include 'actions/ActionBlog/button_join.tpl'}
	{/block}
{/if}